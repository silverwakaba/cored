<?php

namespace App\Repositories;

// Helper
use App\Helpers\ErrorHelper;
use App\Helpers\GeneralHelper;

// Internal
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    private function broadcasterExecute($data = null){
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

    // Equivalent to select | Don't use alongside with excludeSelect
    public function onlySelect(mixed $column = null){
        // Get data type and its data
        $column = GeneralHelper::getType($column);

        // Start the query
        $this->query->select($column);

        // Chainable
        return $this;
    }

    // Equivalent to select certain column while ommit the rest | Don't use alongside with onlySelect | TBC: Needs to be implemented directly inside the repo since it needs to load the modal attributes directly
    public function excludeSelect(mixed $column = null){
        // TBC
        // // Selected by default
        // $default = [
        //     'id',
        // ];

        // // Selected only if timestamp is set to true
        // if(isset($data['timestamps']) && ($data['timestamps'] == true)){
        //     $default[] = 'created_at';
        //     $default[] = 'updated_at';
        // }

        // // Init model
        // $model = new $data['model'];

        // // Get model fillable attributes
        // $fillable = $model->getFillable();

        // // Get model hidden attributes
        // $hidden = $model->getHidden();

        // // Get included column
        // $include = isset($data['include']) ? (array) $data['include'] : [];

        // // Selected column by default
        // $selected = array_values(array_diff(
        //     array_merge($default, $fillable, $include), $hidden,
        // ));

        // // Select column via custom properties
        // if(isset($data['exclude']) && ($data['exclude'] != null)){
        //     $exclude = array_values(array_diff(
        //         $selected, $data['exclude'],
        //     ));

        //     // Return column
        //     return $exclude;
        // }

        // // Return column
        // return $selected;
        // TBC
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

    // Activation data
    public function activation($id, bool $status){
        // Implementing db transaction
        return DB::transaction(function() use($id, $status){
            // Start find query
            $datas = $this->find($id);

            // Update status
            // Boolean column is identified with "is_" prefixes
            $datas = $datas->update([
                'is_active' => $status,
            ]);

            // Call broadcaster if set
            if($this->broadcastClass){
                $this->broadcasterExecute($datas);
            }

            // Return response
            return $datas;
        });
    }
}
