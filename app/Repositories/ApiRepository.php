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
    protected bool $withToken = false;
    protected array $attachments = [];

    // Preload token
    public function withToken(bool $withToken = true) : self{
        $this->withToken = $withToken;
        
        return $this;
    }

    // Preload attachment
    public function attach(array $files) : self{
        $this->attachments = $files;
        
        return $this;
    }

    // Base http request
    protected function baseRequest(){
        $http = Http::withHeaders(
            HeaderHelper::apiHeader()
        );
        
        if($this->withToken){
            $http->withToken(
                CookiesHelper::jwtToken()
            );
        }
        
        return $http;
    }

    // Prepare attachment | TBC
    protected function prepareRequestWithAttachments($http, array $data){
        if(empty($this->attachments)){
            return $http->withOptions(['json' => $data]);
        }

        // Start with data as multipart
        $multipart = [];
        
        // Add regular data fields
        foreach($data as $name => $value){
            $multipart[] = [
                'name'      => $name,
                'contents'  => is_array($value) ? json_encode($value) : $value
            ];
        }
        
        // Add file attachments
        foreach($this->attachments as $name => $file){
            $multipart[] = [
                'name'      => $name,
                'contents'  => fopen($file->getRealPath(), 'r'),
                'filename'  => $file->getClientOriginalName()
            ];
        }
        
        return $http->asMultipart()->withOptions(['multipart' => $multipart]);
    }

    // Get method
    public function get(string $route, array $data = []){
        return $this->baseRequest()->get(route($route, $data));
    }

    // Post method | Add more possible id variable here
    public function post(string $route, array $data = []){
        return $this->baseRequest()->post(route($route, [
            'id' => isset($data['id']) ? $data['id'] : null,
        ]), $data);
    }

    // Put method
    public function put(string $route, array $data = []){
        return $this->baseRequest()->put(route($route), $data);
    }

    // Patch method
    public function patch(string $route, array $data = []){
        return $this->baseRequest()->patch(route($route), $data);
    }

    // Delete method
    public function delete(string $route, array $data = []){
        return $this->baseRequest()->delete(route($route), $data);
    }
}
