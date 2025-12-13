<?php

namespace App\Contracts\Core;

interface MenuRepositoryInterface{
    public function index();
    public function createMenu($data, $position = 'after', $referenceId = null);
    public function updateMenuPosition($menuId, $position, $referenceId);
    public function deleteMenu($menuId);
}





