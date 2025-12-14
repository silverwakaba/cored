<?php

namespace App\Contracts\Core;

interface RoleRepositoryInterface{
    public function syncToPermission($id);
    public function syncToUser($id);
}
