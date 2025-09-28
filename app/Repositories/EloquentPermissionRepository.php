<?php

namespace App\Repositories;

// Helper
use App\Helpers\GeneralHelper;

// Model
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Interface
use App\Contracts\PermissionRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentPermissionRepository extends BaseRepository implements PermissionRepositoryInterface{
    // Constructor
    public function __construct(Permission $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }
}
