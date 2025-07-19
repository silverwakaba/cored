<?php

namespace App\Repositories;

// Helper
use App\Helpers\CookiesHelper;
use App\Helpers\HeaderHelper;

// Interface
use App\Contracts\ApiRepositoryInterface;

// Internal
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class ApiRepository implements ApiRepositoryInterface{
    protected function baseRequest(){
        return Http::withHeaders(
            HeaderHelper::apiHeader()
        );
        // ->withToken(
        //     // CookiesHelper::jwtToken()
        // );
    }

    public function get(string $route, array $data = []){
        return $this->baseRequest()->get(
            route($route, array_merge(request()->all(), $data))
        );
    }

    public function post(string $route, array $data = []){
        return $this->baseRequest()->post(
            route($route), array_merge($data)
        );
    }

    public function put(string $route, array $data = []){
        return $this->baseRequest()->put(
            route($route), array_merge(request()->all(), $data)
        );
    }

    public function patch(string $route, array $data = []){
        return $this->baseRequest()->patch(
            route($route), array_merge(request()->all(), $data)
        );
    }

    // public function delete(string $route, array $data = []){
    //     return $this->baseRequest()->delete(
    //         route($route), array_merge(request()->all(), $data)
    //     );
    // }
}
