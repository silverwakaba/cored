<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\BaseModule;

// Interface
use App\Contracts\Core\BaseModuleRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentBaseModuleRepository extends BaseRepository implements BaseModuleRepositoryInterface{
    // Constructor
    public function __construct(BaseModule $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
