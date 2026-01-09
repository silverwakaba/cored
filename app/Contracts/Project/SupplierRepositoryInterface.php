<?php

namespace App\Contracts\Project;

interface SupplierRepositoryInterface{
    public function createWithUser(array $supplierData, array $userData);
    public function completeSupplierProfile(string $token, array $supplierData, array $userData);
    public function assignUser(string $userId, int $supplierId);
}
