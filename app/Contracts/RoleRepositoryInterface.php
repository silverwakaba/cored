<?php

namespace App\Contracts;

interface RoleRepositoryInterface{
    public function syncToPermission($id);
    public function syncToUser($id);
}
