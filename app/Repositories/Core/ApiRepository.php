<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\CookiesHelper;
use App\Helpers\Core\GeneralHelper;
use App\Helpers\Core\HeaderHelper;

// Interface
use App\Contracts\Core\ApiRepositoryInterface;

// Internal
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class ApiRepository implements ApiRepositoryInterface{
    // Property
    protected bool $withToken = false;
    protected array $attachments = [];

    // Preload JWT token
    public function withToken(bool $withToken = true) : self{
        // Define 'withToken' property
        $this->withToken = $withToken;
        
        // Chainable
        return $this;
    }

    // Preload attachment
    public function withAttachment() : self{
        // Define 'withAttachment' property
        $this->attachments = request()->allFiles();
        
        // Chainable
        return $this;
    }

    // Base http request
    protected function baseRequest(){
        // Load basic http request
        $http = Http::withHeaders(
            HeaderHelper::apiHeader()
        );
        
        // Attach token
        if($this->withToken){
            $http->withToken(CookiesHelper::jwtToken());
        }

        // Attach an attachment
        if($this->attachments){
            foreach($this->attachments as $key => $object){
                // Handle multiple files from a single input (e.g., name="file[]")
                if(is_array($object)){
                    foreach($object as $index => $file){
                        $http->attach("{$key}[{$index}]", fopen($file->getRealPath(), 'r'), $file->getClientOriginalName());
                    }
                }
                // Handle single file
                else{
                    $http->attach($key, fopen($object->getRealPath(), 'r'), $object->getClientOriginalName());
                }
            }
        }
        
        // Extendable
        return $http;
    }

    // Get method
    public function get(string $route, array $data = []){
        return $this->baseRequest()->get(route($route, $data));
    }

    // Post method | Add more possible id variable here
    public function post(string $route, array $data = []){
        return $this->baseRequest()->post(route($route, [
            'id'    => isset($data['id']) ? $data['id'] : null,
            'token' => isset($data['token']) ? $data['token'] : null,
        ]), $data);
    }

    // Put method
    public function put(string $route, array $data = []){
        return $this->baseRequest()->put(route($route, [
            'id'    => isset($data['id']) ? $data['id'] : null,
            'token' => isset($data['token']) ? $data['token'] : null,
        ]), $data);
    }

    // Patch method
    public function patch(string $route, array $data = []){
        return $this->baseRequest()->patch(route($route, [
            'id'    => isset($data['id']) ? $data['id'] : null,
            'token' => isset($data['token']) ? $data['token'] : null,
        ]), $data);
    }

    // Delete method
    public function delete(string $route, array $data = []){
        return $this->baseRequest()->delete(route($route, [
            'id'    => isset($data['id']) ? $data['id'] : null,
            'token' => isset($data['token']) ? $data['token'] : null,
        ]), $data);
    }
}
