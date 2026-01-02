<?php

namespace App\Contracts\Core;

interface MenuRepositoryInterface{
    public function index();
    public function list();
    public function read($id);
    public function createMenu($data, $position = 'after', $referenceId = null);
    public function updateMenu($id, $data);
    public function updateMenuPosition($menuId, $position, $referenceId);
    public function deleteMenu($menuId);
}
