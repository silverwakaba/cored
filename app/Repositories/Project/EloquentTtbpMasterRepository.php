<?php

namespace App\Repositories\Project;

// Model
use App\Models\Project\TtbpMaster;

// Interface
use App\Contracts\Project\TtbpMasterRepositoryInterface;

// Base
use App\Repositories\Core\BaseRepository;

class EloquentTtbpMasterRepository extends BaseRepository implements TtbpMasterRepositoryInterface{
    // Constructor
    public function __construct(TtbpMaster $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
