<?php

namespace App\Contracts;

interface ApiRepositoryInterface{
    public function get(string $route, array $data = []);
    public function post(string $route, array $data = []);
    public function put(string $route, array $data = []);
    public function patch(string $route, array $data = []);
    // public function delete(string $route, array $data = []);
}
