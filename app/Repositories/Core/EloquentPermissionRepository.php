<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
