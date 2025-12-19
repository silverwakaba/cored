<?php

namespace App\Observers\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Mailer
use App\Mail\Core\UserVerifyEmail;

// Model
use App\Models\Core\User;

// Internal
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserObserver{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user) : void{
        // Implementing db transaction
        DB::transaction(function() use($user){
            // Create token request
            $request = $user->userRequests()->create([
                'base_requests_id'  => 1,
                'users_id'          => $user->id,
                'token'             => GeneralHelper::randomToken(),
            ]);

            // Send validation email
            // Mail::to($user->email)->send(new UserVerifyEmail($request->id));
        });
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user) : void{
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user) : void{
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user) : void{
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user) : void{
        //
    }
}
