<?php

namespace App\Contracts\Project;

interface SupplierRepositoryInterface{
    public function createWithUser(array $supplierData, array $userData, ?string $role = 'Supplier');
}
