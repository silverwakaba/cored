<?php

namespace App\Repositories\Project;

// Model
use App\Models\Project\PurchaseOrder;

// Interface
use App\Contracts\Project\PurchaseOrderRepositoryInterface;

// Base
use App\Repositories\Core\BaseRepository;

class EloquentPurchaseOrderRepository extends BaseRepository implements PurchaseOrderRepositoryInterface{
    // Constructor
    public function __construct(PurchaseOrder $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
