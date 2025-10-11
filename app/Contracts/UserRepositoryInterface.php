<?php

namespace App\Contracts;

interface UserRepositoryInterface{
    public function register();
    public function modify($id, array $data);
    public function verifyAccount($id);
}
