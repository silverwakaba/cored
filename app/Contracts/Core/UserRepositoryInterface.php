<?php

namespace App\Contracts\Core;

interface UserRepositoryInterface{
    public function register();
    public function modify($id, array $data);
    public function verifyAccount($id);
    public function search($data);
    public function resetPassword($data);
}





