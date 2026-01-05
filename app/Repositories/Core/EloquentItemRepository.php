<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\ItemMaster;

// Interface
use App\Contracts\Core\ItemRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentItemRepository extends BaseRepository implements ItemRepositoryInterface{
    // Constructor
    public function __construct(ItemMaster $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}

