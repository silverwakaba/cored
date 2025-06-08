<?php

namespace App\Repositories;

// Helper
use App\Helpers\GeneralHelper;

// Internal
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

// External
use Yajra\DataTables\Facades\DataTables;

abstract class BaseRepository{
    // Property
    protected $model;
    protected $shouldUseDatatable = false;

    // Constructor
    public function __construct(Model $model){
        $this->model = $model;
        $this->query = $model->query();
    }

    // Using datatable
    public function useDatatable(){
        // Change property 'shouldUseDatatable' status
        $this->shouldUseDatatable = true;
        
        // Chainable
        return $this;
    }

    // Minimal select for better performance
    public function minSelect(bool $status = false){
        // Start the query
        $this->query->select('id');
        
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

    // Load active data
    public function whereActive(bool $status = false){
        // Start the query
        $this->query->where('active', $status);
        
        // Chainable
        return $this;
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

            // Return response
            return $datas;
        });
    }

    // Update data
    public function update($id, array $data){
        // Implementing db transaction
        return DB::transaction(function() use($id, $data){
            // Start update with pessimistic locking
            $datas = $this->query->select('id')->lockForUpdate()->find($id);

            // Populate data as is (we don't use mass assignment in case of partial update -- eg. only updating 'name')
            foreach($data as $key => $value){
                $datas[$key] = $value;
            }

            // Save updated data
            $datas->save();

            // Return response
            return $datas;
        });
    }

    // Delete data
    public function delete($id){
        // Implementing db transaction
        return DB::transaction(function() use($id){
            // Start find query
            $datas = $this->minSelect()->find($id);

            // Delete data
            $datas->delete();

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
            $datas->update([
                'active' => $status,
            ]);

            // Return response
            return $datas;
        });
    }
}
