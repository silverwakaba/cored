<?php

namespace App\Models\Project;

use App\Models\Core\User as CoreUser;

class User extends CoreUser{
    /**
     * The guard name for Spatie Permission.
     * This tells Spatie which guard this model uses.
     *
     * @var string
     */
    protected $guard_name = 'web';

    // /**
    //  * The attributes that are mass assignable.
    //  * 
    //  * IMPORTANT: This includes all fillable attributes from Core\User.
    //  * If Core\User::$fillable changes, you must update this array accordingly.
    //  * 
    //  * @var list<string>
    //  */
    // protected $fillable = [
    //     // From Core\User
    //     'name',
    //     'email',
    //     'password',
    //     'token',
    //     'token_expire_at',
    //     'is_active',
    //     'email_verified_at',
    //     // Add project-specific fillable attributes below
    //     // Example: 'project_specific_field',
    // ];

    // /**
    //  * The attributes that should be hidden for serialization.
    //  * 
    //  * IMPORTANT: This includes all hidden attributes from Core\User.
    //  * If Core\User::$hidden changes, you must update this array accordingly.
    //  * 
    //  * @var list<string>
    //  */
    // protected $hidden = [
    //     // From Core\User
    //     'email_verified_at',
    //     'password',
    //     'token',
    //     'token_expire_at',
    //     // Add project-specific hidden attributes below
    //     // Example: 'project_specific_sensitive_field',
    // ];

    // /**
    //  * Get the attributes that should be cast.
    //  * Merged with parent casts.
    //  *
    //  * @return array<string, string>
    //  */
    // protected function casts() : array{
    //     return array_merge(parent::casts(), [
    //         // Add project-specific casts here if needed
    //         // Example: 'project_date_field' => 'datetime',
    //     ]);
    // }

    /**
     * Get the supplier associated with this user (vendor portal user)
     */
    public function supplier(){
        return $this->hasOne(Supplier::class, 'users_id', 'id');
    }

    /**
     * Get all suppliers created by this user
     */
    public function createdSuppliers(){
        return $this->hasMany(Supplier::class, 'created_by', 'id');
    }

    /**
     * Get all suppliers updated by this user
     */
    public function updatedSuppliers(){
        return $this->hasMany(Supplier::class, 'updated_by', 'id');
    }
}
