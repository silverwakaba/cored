<?php

namespace App\Observers;

// Helper
use App\Helpers\GeneralHelper;

// Mailer
use App\Mail\UserVerifyEmail;

// Model
use App\Models\User;

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
            $request = $user->hasOneUserRequest()->create([
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
