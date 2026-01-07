<?php

namespace App\Repositories\Project;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\BaseRequest;
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
            // Get base request for supplier profile completion
            $baseRequest = BaseRequest::select(['id'])->where([
                ['name', '=', 'Supplier Profile Completion'],
            ])->whereHas('baseModule', function($query){
                $query->where('name', 'Account Management');
            })->first();
            
            // Create user
            $user = User::create($userData);
            
            // Assign role to the user
            $role = $user->assignRole('Supplier');

            // Create request for supplier activation token
            $request = $user->userRequests()->create([
                'base_requests_id'  => $baseRequest->id,
                'token'             => GeneralHelper::randomToken(),
            ]);

            // Create request for supplier activation token
            $supplier = $user->supplier()->create([
                // Foreign keys
                'base_qualification_id'     => $supplierData['base_qualification_id'],     // 363: Kecil
                'base_business_entity_id'   => $supplierData['base_business_entity_id'],   // 265: PT
                
                // Basic information
                'code'                      => $supplierData['code'],
                'name'                      => $supplierData['name'],
                'credit_day'                => $supplierData['credit_day'],
            ]);

            // Return user with supplier relation loaded
            return $user->load('supplier');
        });
    }
}
