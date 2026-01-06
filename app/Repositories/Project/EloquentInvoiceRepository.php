<?php

namespace App\Repositories\Project;

// Model
use App\Models\Project\Invoice;

// Interface
use App\Contracts\Project\InvoiceRepositoryInterface;

// Base
use App\Repositories\Core\BaseRepository;

class EloquentInvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface{
    // Constructor
    public function __construct(Invoice $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
