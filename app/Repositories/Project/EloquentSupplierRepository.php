<?php

namespace App\Repositories\Project;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Project\User; // User model belongs to Project
use App\Models\Project\Supplier;

// Interface
use App\Contracts\Project\SupplierRepositoryInterface;

// Base
use App\Repositories\Core\BaseRepository;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentSupplierRepository extends BaseRepository implements SupplierRepositoryInterface{
    // Constructor
    public function __construct(Supplier $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }

    /**
     * Create supplier with associated user
     * 
     * @param array $supplierData Supplier data
     * @param array $userData User data (name, email, password, etc.)
     * @return \App\Models\Project\Supplier
     */
    public function createWithUser(array $supplierData, array $userData){
        // Implementing db transaction
        return DB::transaction(function() use($supplierData, $userData){
            // Create user
            $user = User::create($userData);
            
            // Assign role to the user
            $role = $user->assignRole('Supplier');

            // Create request for supplier activation token
            $request = $user->userRequests()->create([
                'base_requests_id'  => 4,
                'users_id'          => $user->id,
                'token'             => GeneralHelper::randomToken(),
            ]);

            // Create request for supplier activation token
            $supplier = $user->supplier()->create([
                // Foreign keys
                'base_qualification_id'     => $supplierData['base_qualification_id'],     // 363: Kecil
                'base_business_entity_id'   => $supplierData['base_business_entity_id'],   // 265: PT
                'base_bank_id'              => $supplierData['base_bank_id'],
                
                // Basic information
                'code'                      => $supplierData['code'],
                'name'                      => $supplierData['name'],
                'credit_day'                => $supplierData['credit_day'],
                
                // Address
                'address_1'                 => $supplierData['address_1'],
                'address_2'                 => $supplierData['address_2'],
                
                // Contact
                'telp'                      => $supplierData['telp'],
                'fax'                       => $supplierData['fax'],
                
                // Tax information
                'npwp'                      => $supplierData['npwp'],
                'npwp_address'              => $supplierData['npwp_address'],
                
                // Bank information
                'bank_account_name'         => $supplierData['bank_account_name'],
                'bank_account_number'       => $supplierData['bank_account_number'],
                
                // Additional information
                'pkp'                       => $supplierData['pkp'],
                'nib'                       => $supplierData['nib'],
                'notes'                     => $supplierData['notes'],

                'created_by'                => auth()->user()->id ?? null,
                'updated_by'                => auth()->user()->id ?? null,
            ]);

            // Return user with supplier relation loaded
            return $user->load('supplier');
        });
    }
}
