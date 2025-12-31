<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\ErrorHelper;
use App\Helpers\Core\GeneralHelper;

// Internal
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// External
use Yajra\DataTables\Facades\DataTables;

abstract class BaseRepository{
    // Property
    protected $model;
    protected $broadcastClass = null;
    protected $broadcastAction = null;
    protected $shouldUseDatatable = false;

    // Constructor
    public function __construct(Model $model){
        $this->model = $model;
        $this->query = $model->query();
    }

    // Setting broadcaster
    public function broadcaster($broadcast = null, $action = null){
        $this->broadcastClass = $broadcast;
        $this->broadcastAction = $action;
        
        // Chainable
        return $this;
    }

    // Execute broadcaster
    protected function broadcasterExecute($data = null){
        // Handle broadcast if available
        if(isset($this->broadcastClass) && class_exists($this->broadcastClass)){
            try{
                $this->broadcastClass::dispatch($data, $this->broadcastAction);
            } catch(\Throwable $th) {
                // Handle error silently
            } finally {
                // Reset broadcast properties after use
                $this->broadcastClass = null;
                $this->broadcastAction = null;
            }
        }
    }

    // Using datatable
    public function useDatatable(){
        // Change property 'shouldUseDatatable' status
        $this->shouldUseDatatable = true;
        
        // Chainable
        return $this;
    }

    // Minimal select for better performance
    public function minSelect(){
        // Start the query
        $this->query->select('id');
        
        // Chainable
        return $this;
    }

    // Equivalent to select | Can be used alongside with excludeSelect
    public function onlySelect(mixed $column = null){
        // Get data type and its data
        $column = GeneralHelper::getType($column);

        // Start the query
        $this->query->select($column);

        // Chainable
        return $this;
    }

    // Equivalent to select certain column while ommit the rest | Can be used alongside with onlySelect
    public function excludeSelect(mixed $column = null){
        // Get data type and its data (convert to array)
        $excludeColumns = GeneralHelper::getType($column);
        
        // Get currently selected columns from query builder (if any)
        $currentColumns = null;
        if(isset($this->query)){
            try {
                $reflection = new \ReflectionClass($this->query);
                if($reflection->hasProperty('columns')){
                    $columnsProperty = $reflection->getProperty('columns');
                    $columnsProperty->setAccessible(true);
                    $currentColumns = $columnsProperty->getValue($this->query);
                    // Filter out null values and normalize
                    if($currentColumns){
                        $currentColumns = array_filter($currentColumns, function($col){
                            return $col !== null && $col !== '*';
                        });
                        $currentColumns = array_values($currentColumns);
                        // Remove table prefix and aliases if exists
                        $currentColumns = array_map(function($col){
                            // Handle "table.column" or "column as alias"
                            $parts = preg_split('/\s+as\s+/i', $col);
                            $col = trim($parts[0]);
                            // Remove table prefix
                            $dotPos = strrpos($col, '.');
                            if($dotPos !== false){
                                $col = substr($col, $dotPos + 1);
                            }
                            return $col;
                        }, $currentColumns);
                    }
                }
            } catch(\Exception $e) {
                // Continue - will use all columns from table
            }
        }
        
        // Determine base columns to work with
        $baseColumns = null;
        
        // If there are already selected columns, use those as base
        if($currentColumns && !empty($currentColumns)){
            $baseColumns = $currentColumns;
        } else {
            // Otherwise, get all columns from table
            // Get table name - try multiple methods
            $tableName = null;
            
            // Method 1: Get from model property
            if($this->model){
                $tableName = $this->model->getTable();
            }
            
            // Method 2: Get from query builder's model
            if(!$tableName && isset($this->query)){
                try {
                    $model = $this->query->getModel();
                    if($model){
                        $tableName = $model->getTable();
                    }
                } catch(\Exception $e) {
                    // Continue to next method
                }
            }
            
            // Method 3: Get from query builder's from property using reflection
            if(!$tableName && isset($this->query)){
                try {
                    $reflection = new \ReflectionClass($this->query);
                    if($reflection->hasProperty('from')){
                        $fromProperty = $reflection->getProperty('from');
                        $fromProperty->setAccessible(true);
                        $from = $fromProperty->getValue($this->query);
                        if($from){
                            // Handle array or string
                            $tableName = is_array($from) ? $from[0] : $from;
                            // Remove table prefix if exists
                            $prefix = $this->query->getConnection()->getTablePrefix();
                            if($prefix && strpos($tableName, $prefix) === 0){
                                $tableName = substr($tableName, strlen($prefix));
                            }
                        }
                    }
                } catch(\Exception $e) {
                    // Continue
                }
            }
            
            // If still no table name, throw error
            if(!$tableName){
                throw new \RuntimeException('Unable to determine table name for excludeSelect. Model must be properly initialized in repository constructor.');
            }
            
            // Get all columns from the table
            $baseColumns = Schema::getColumnListing($tableName);
        }
        
        // Exclude specified columns from base columns
        $selectedColumns = array_values(array_diff($baseColumns, $excludeColumns));
        
        // If no columns left after exclusion, at least select 'id' to avoid errors
        if(empty($selectedColumns)){
            $selectedColumns = ['id'];
        }
        
        // Start the query with selected columns
        $this->query->select($selectedColumns);
        
        // Chainable
        return $this;
    }

    // Load relation
    public function withRelation(mixed $relation = null){
        // Get data type and its data
        $relations = GeneralHelper::getType($relation);

        // Load relation
        $this->query->with($relations);
        
        // Chainable
        return $this;
    }

    // Load trashed
    public function loadTrashed(string $type){
        // Load based on type
        if($type == 'exclude'){
            // Only trashed (Soft deleted without active data)
            $this->query->onlyTrashed();
        } else {
            // Include trashed (Active data + Soft deleted)
            $this->query->withTrashed();
        }

        // Chainable
        return $this;
    }

    // Load active data
    public function whereActive(bool $status = false){
        // Start the query
        $this->query->where('active', $status);
        
        // Chainable
        return $this;
    }

    // Sort data
    public function sort(array $column){
        // Init query
        $datas = $this->query;

        // Start "order by" query
        foreach($column as $col => $sort){
            $datas = $datas->orderBy($col, $sort);
        }

        // Return response
        return $datas;
    }

    // All data
    public function all(){
        // Start get query
        $datas = $this->query->get();

        // Return response as datatable
        if($this->shouldUseDatatable == true){
            return DataTables::of($datas)->toJson();
        }

        // Return response
        return $datas;
    }

    // Find data
    public function find($id){
        // Start find query
        $datas = $this->query->find($id);

        // Return response
        return $datas;
    }

    // Create data
    public function create(array $data){
        // Implementing db transaction
        return DB::transaction(function() use($data){
            // Start create query
            $datas = $this->query->create($data);

            // Call broadcaster if set
            if($this->broadcastClass){
                $this->broadcasterExecute($datas);
            }

            // Return response
            return $datas;
        });
    }

    // Update data
    public function update($id, array $data){
        // Implementing db transaction
        return DB::transaction(function() use($id, $data){
            // Start update with pessimistic locking
            $datas = $this->query->lockForUpdate()->find($id);

            // Populate with an array of attributes
            $datas->fill($data);

            // Save updated data
            $datas->save();

            // Call broadcaster if set
            if($this->broadcastClass){
                $this->broadcasterExecute($datas);
            }

            // Return response
            return $datas;
        });
    }

    // Delete data (multiple data available through $ids)
    public function delete($id, string $type = null){
        // Get data type and its data, then convert it as array
        $ids = GeneralHelper::getType($id);

        // Implementing db transaction
        return DB::transaction(function() use($ids, $type){
            // Start where in query
            $datas = $this->query->select('id')->whereIn('id', $ids);

            // Delete based on type
            if($type == 'hard'){
                // Hard delete
                $datas->forceDelete();
            } else {
                // Soft delete
                $datas->delete();
            }

            // Call broadcaster if set
            if($this->broadcastClass){
                $this->broadcasterExecute(null); // null data
            }

            // Return response
            return $datas;
        });
    }

    // Restore data
    public function restore($id){
        // Get data type and its data, then convert it as array
        $ids = GeneralHelper::getType($id);

        // Implementing db transaction
        return DB::transaction(function() use($ids){
            // Start where in query
            $datas = $this->query->withTrashed()->select('id')->whereIn('id', $ids);

            // Restore data
            $datas = $datas->restore();

            // Call broadcaster if set
            if($this->broadcastClass){
                $this->broadcasterExecute($datas);
            }

            // Return response
            return $datas;
        });
    }

    // Activation data (multiple data available through $ids)
    public function activation($id, bool $status = null){
        // Get data type and its data, then convert it as array
        $ids = GeneralHelper::getType($id);

        // Implementing db transaction
        return DB::transaction(function() use($ids, $status){
            // Start find query
            $datas = $this->query->select('id', 'is_active')->whereIn('id', $ids)->get();

            // If status is provided, set all to that status
            if($status !== null){
                // Update all data to the provided status
                $this->query->whereIn('id', $ids)->update([
                    'is_active' => $status,
                ]);

                // Determine action performed
                $action = $status ? 'activated' : 'deactivated';

                // Return response with action info
                return [
                    'data'      => $datas->count(),
                    'action'    => $action,
                ];
            }

            // If status is not provided, toggle each data individually
            $activatedCount = 0;
            $deactivatedCount = 0;

            foreach($datas as $data){
                $newStatus = !$data->is_active;
                
                // Update individual data
                $data->update([
                    'is_active' => $newStatus,
                ]);

                // Count actions
                if($newStatus){
                    $activatedCount++;
                } else {
                    $deactivatedCount++;
                }
            }

            // Determine action performed (mixed if both actions occurred)
            if($activatedCount > 0 && $deactivatedCount > 0){
                $action = 'toggled';
            } elseif($activatedCount > 0){
                $action = 'activated';
            } else {
                $action = 'deactivated';
            }

            // Call broadcaster if set
            if($this->broadcastClass){
                $this->broadcasterExecute($datas);
            }

            // Return response with action info
            return [
                'data'              => $datas->count(),
                'action'            => $action,
                'activated_count'   => $activatedCount,
                'deactivated_count' => $deactivatedCount,
            ];
        });
    }
}
