<?php

namespace App\Repositories\Project;

// Model
use App\Models\Project\TtbpDetail;

// Interface
use App\Contracts\Project\TtbpDetailRepositoryInterface;

// Base
use App\Repositories\Core\BaseRepository;

class EloquentTtbpDetailRepository extends BaseRepository implements TtbpDetailRepositoryInterface{
    // Constructor
    public function __construct(TtbpDetail $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
