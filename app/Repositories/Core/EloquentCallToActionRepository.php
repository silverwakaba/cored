<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
// use App\Models\Core\User;
// use Spatie\Permission\Models\Permission;
// use Spatie\Permission\Models\Role;
use App\Models\Core\UserCtaMessage;

// Interface
use App\Contracts\Core\CallToActionRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class EloquentCallToActionRepository implements CallToActionRepositoryInterface{
    // Constructor
    // public function __construct(){
    //     parent::__construct();
    //     // $this->model = parent::__construct($model);
    //     // $this->query = $model->query();
    // }

    // Messages
    public function messages($datas){
        // Send as webhook for guest
        Http::post("https://discord.com/api/webhooks/1190930890625404948/glIFNLTZ2ea7_92n_pzSZtZn_aGjKzKHP-2Pch7rB14XDl_Xfawv2bB9DdJ22oPi1A6l", [
            "embeds"    => [
                [
                    "title"         => "Silverspoon message alert!",
                    "description"   => "Name: $datas[name]\nEmail: $datas[email]\nSubject: $datas[subject]\nMessage: $datas[message]",
                    "color"         => "7506394",
                ]
            ],
        ]);

        // Save the message for registered user
        if(auth()->guard('api')->user()){
            // Implementing db transaction
            return DB::transaction(function() use($datas){
                // Start create query
                $data = UserCtaMessage::create($datas);

                // Return response
                return $data;
            });
        }
    }
}
