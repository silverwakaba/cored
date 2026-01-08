<?php

namespace App\Contracts\Project;

interface SupplierRepositoryInterface{
    public function createWithUser(array $supplierData, array $userData);
    
    /**
     * Find supplier profile completion request by token
     * 
     * @param string $token Supplier profile completion token
     * @return \App\Models\Project\UserRequest|null
     */
    public function findSupplierProfileCompletionByToken(string $token);
    
    /**
     * Complete supplier profile using token
     * 
     * @param string $token Supplier profile completion token
     * @param array $supplierData Supplier profile data to update
     * @return \App\Models\Project\Supplier
     */
    public function completeSupplierProfile(string $token, array $supplierData);
}
