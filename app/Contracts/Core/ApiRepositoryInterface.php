<?php

namespace App\Contracts\Core;

interface ApiRepositoryInterface{
    public function withToken(bool $withToken = true) : self;
    public function withAttachment() : self;
    public function get(string $route, array $data = []);
    public function post(string $route, array $data = []);
    public function put(string $route, array $data = []);
    public function patch(string $route, array $data = []);
    public function delete(string $route, array $data = []);
}





