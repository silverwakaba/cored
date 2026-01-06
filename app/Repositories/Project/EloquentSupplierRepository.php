<?php

namespace App\Repositories\Project;

// Model
use App\Models\Project\Supplier;

// Interface
use App\Contracts\Project\SupplierRepositoryInterface;

// Base
use App\Repositories\Core\BaseRepository;

class EloquentSupplierRepository extends BaseRepository implements SupplierRepositoryInterface{
    // Constructor
    public function __construct(Supplier $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
