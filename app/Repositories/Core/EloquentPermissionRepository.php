<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\User;
use App\Models\Core\Permission;
use App\Models\Core\Role;

// Interface
use App\Contracts\Core\PermissionRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentPermissionRepository extends BaseRepository implements PermissionRepositoryInterface{
    // Constructor
    public function __construct(Permission $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
