<?php

namespace App\Helpers;

use Faker\Factory;
use Illuminate\Support\Str;

class FileHelper{
    // Disk properties
    public static function disk(){
        $datas = config('filesystems.disks.s3Custom');
        
        return $datas;
    }

    // Path directory
    public static function directory($type){
        switch($type){
            case 'avatar': return 'app/avatar'; break;
        }
    }

    // Avatar
    public static function avatar($path = null){
        $disk = self::disk();

        if($path == null){
            $rangeAvatar = Factory::create()->numberBetween(1, 5);

            return 'https://static.pub.spnd.uk/system/internal/image/avatar/avatar-' . $rangeAvatar . '.png';
        }
        
        return Str::of($disk['domain'] . '/')->append(self::directory('avatar') . '/' . $path);
    }
}
