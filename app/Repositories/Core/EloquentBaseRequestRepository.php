<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\BaseRequest;

// Interface
use App\Contracts\Core\BaseRequestRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentBaseRequestRepository extends BaseRepository implements BaseRequestRepositoryInterface{
    // Constructor
    public function __construct(BaseRequest $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
