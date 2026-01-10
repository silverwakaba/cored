<?php

namespace App\Models\Core;

use App\Observers\Core\UserObserver;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements JWTSubject{
    /** @use HasFactory<\Database\Factories\Core\UserFactory> */
    use HasFactory, Notifiable, HasUlids, HasRoles;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'token_expire_at',
        'is_active',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'email_verified_at',
        'password',
        'token',
        'token_expire_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts() : array{
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(){
        return [];
    }

    public function userRequests(){
        return $this->hasMany(UserRequest::class, 'users_id');
    }

    public function userCtaMessages(){
        return $this->hasMany(UserCtaMessage::class, 'users_id');
    }

    public function notifications(){
        return $this->hasMany(Notification::class, 'users_id', 'id');
    }

    // Menus that this user can access even without role (included)
    public function includedMenus(){
        return $this->belongsToMany(Menu::class, 'menu_user_includes');
    }

    // Menus that this user cannot access even with role (excluded)
    public function excludedMenus(){
        return $this->belongsToMany(Menu::class, 'menu_user_excludes');
    }
}
