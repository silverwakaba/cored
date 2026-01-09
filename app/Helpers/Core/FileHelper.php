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
// I'll try to implement FTP and any other file system later
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
        // Define whitelist of predefined directories
        $whitelist = [
            'avatar',
            'cta/message',
            'invoice',
            'po',
            'statement',
            'supplier',
            'test',
            'ttbp',
        ];

        // Determine directory
        // If directory is null or empty, use default
        // If directory is in whitelist, use it as is
        // If directory is not in whitelist but has a value, use the provided value
        if($directory === null || $directory === ''){
            $directory = 'general';
        }

        // Set project (sluggable project name) + document directory (e.g: The project name is silverspoon and document is avatar, so the result is: silverspoon/avatar/, etc)
        $this->directory = Str::of(strtolower($directory))->prepend(strtolower(Str::slug(config('app.name'), '-')) . '/')->finish('/');
        
        // Chainable
        return $this;
    }

    // Upload | e.g: (new FileHelper)->disk()->directory('general')->upload(request()->allFiles())
    public function upload($path){
        return GeneralHelper::safe(function() use($path){
            // Init storage
            $storage = Storage::disk($this->disk);

            // Upload files
            foreach(GeneralHelper::getType($path) as $key => $object){
                // Handle multiple files from a single input (e.g., name="file[]")
                if(is_array($object)){
                    foreach($object as $index => $file){
                        $uploaded["{$key}[{$index}]"] = Str::replace('//', '/', $storage->put($this->directory, new File($file)));
                    }
                }
                // Handle single file
                else{
                    $uploaded[$key] = Str::replace('//', '/', $storage->put($this->directory, new File($object)));
                }
            }

            // Return response
            return $uploaded;
        }, ['status' => 409, 'message' => false], false, []);
    }

    // Get | e.g: (new FileHelper)->disk()->get('/dir')
    public function get($path, $private = false, $download = false, $proxy = true){
        return GeneralHelper::safe(function() use($path, $private, $download, $proxy){
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
        }, ['status' => 409, 'message' => false], false, '');
    }

    // Delete | e.g: (new FileHelper)->disk()->delete('/dir')
    public function delete($path){
        return GeneralHelper::safe(function() use($path){
            // Init storage
            $storage = Storage::disk($this->disk);

            // Delete files
            foreach(GeneralHelper::getType($path) as $key => $object){
                $deleted[$key] = $storage->delete($object);
            }

            // Return response
            return $deleted;
        }, ['status' => 409, 'message' => false], false, []);
    }
}
