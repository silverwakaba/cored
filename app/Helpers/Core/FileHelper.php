<?php

namespace App\Helpers\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Internal
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// External
use Carbon\Carbon;
use Faker\Factory;

// Currently this file helper is only designed to be used with S3-compatible file system
class FileHelper{
    // Property
    protected $disk;
    protected $directory;
    protected $expire;

    // Link expire in minutes
    public function expire($expire = 30){
        // Set disk
        $this->expire = $expire;

        // Chainable
        return $this;
    }

    // Set Disk (s3 only)
    public function disk($disk = 's3Custom'){
        // Set disk
        $this->disk = $disk;
        
        // Chainable
        return $this;
    }

    // Set directory
    public function directory($directory){
        // Determine directory
        switch($directory){
            // Avatar
            case 'avatar': $directory; break;

            // CTA message
            case 'cta/message': $directory; break;

            // Test
            case 'test': $directory; break;
            
            // Default
            default: $directory = 'general'; break;
        }

        // Set project + document directory (e.g: The project name is silverspoon and document is avatar, so the result is: silverspoon/avatar/, etc)
        $this->directory = Str::of($directory)->prepend(strtolower(config('app.name')) . '/')->finish('/');
        
        // Chainable
        return $this;
    }

    // Upload
    public function upload($path){
        // Init storage
        $storage = Storage::disk($this->disk);

        // Upload files
        foreach(GeneralHelper::getType($path) as $key => $object){
            // Handle multiple files from a single input (e.g., name="file[]")
            if(is_array($object)){
                foreach($object as $index => $file){
                    $uploaded["{$key}[{$index}]"] = Str::replace('//', '/', $storage->put($this->directory, $file));
                }
            }
            // Handle single file
            else{
                $uploaded[$key] = Str::replace('//', '/', $storage->put($this->directory, new File($object)));
            }
        }

        // Return response
        return $uploaded;
    }

    // Get
    public function get($path, $private = false, $download = false, $proxy = true){
        // Init storage
        $storage = Storage::disk($this->disk);

        // Handle download option
        if($download == false){
            // No params
            $download_params = [];
        } else {
            // Mime typee + new file name
            $download_params = [
                'ResponseContentType'           => 'application/octet-stream',
                'ResponseContentDisposition'    => 'attachment; filename=' . Str::replace('+', '_', urlencode(Str::of($path)->basename())),
            ];
        }

        // Handle access
        if($private == false){
            // Public access
            $url = $storage->url($path);
        } else {
            // Private access
            $url = $storage->temporaryUrl(
                $path, now()->addMinutes($this->expire), $download_params
            );
        }

        // Handle proxy
        if($proxy == false){
            // No proxy
            $get = $url;
        } else {
            // Parse original url
            $parse_schema = parse_url($url);

            // Proxy the url
            $get = Str::replace("{$parse_schema['scheme']}://{$parse_schema['host']}", config("filesystems.disks.{$this->disk}.domain"), $url);
        }

        // Return response
        return $get;
    }

    // Delete
    public function delete($path){
        // Init storage
        $storage = Storage::disk($this->disk);

        // Delete files
        foreach(GeneralHelper::getType($path) as $key => $object){
            $deleted[$key] = $storage->delete($object);
        }

        // Return response
        return $deleted;
    }
}






