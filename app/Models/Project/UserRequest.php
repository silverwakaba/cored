<?php

namespace App\Models\Project;

use App\Models\Core\UserRequest as CoreUserRequest;
use App\Models\Core\BaseRequest;
use App\Models\Project\User;
use App\Models\Project\Supplier;

class UserRequest extends CoreUserRequest{
    /**
     * Override user relationship to use Project\User instead of Core\User
     * This allows access to project-specific user relationships (e.g., supplier)
     */
    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id')->select('id', 'name', 'email');
    }

    /**
     * Get the base request associated with this user request via base_requests_id
     * This relationship connects UserRequest to BaseRequest
     */
    public function baseRequest(){
        return $this->belongsTo(BaseRequest::class, 'base_requests_id', 'id')->select('id', 'name');
    }

    /**
     * Get the supplier associated with this user request via users_id
     * This relationship connects UserRequest to Supplier through the shared users_id
     * 
     * Note: To load baseQualification, baseBusinessEntity, and baseBank, use nested eager loading:
     * UserRequest::with(['supplier.baseQualification', 'supplier.baseBusinessEntity', 'supplier.baseBank'])
     */
    public function supplier(){
        return $this->hasOne(Supplier::class, 'users_id', 'users_id');
    }
}
