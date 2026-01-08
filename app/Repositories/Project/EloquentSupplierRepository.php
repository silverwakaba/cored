<?php

namespace App\Repositories\Project;

// Helper
use App\Helpers\Core\FileHelper;
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\BaseRequest;
use App\Models\Project\User; // User model belongs to Project
use App\Models\Project\UserRequest; // UserRequest model belongs to Project
use App\Models\Project\Supplier;

// Interface
use App\Contracts\Project\SupplierRepositoryInterface;

// Base
use App\Repositories\Core\BaseRepository;

// Internal
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EloquentSupplierRepository extends BaseRepository implements SupplierRepositoryInterface{
    // Constructor
    public function __construct(Supplier $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }

    /**
     * Get base request for supplier profile completion
     * 
     * @return \App\Models\Core\BaseRequest|null
     */
    private function getSupplierProfileCompletionBaseRequest(){
        return BaseRequest::select(['id'])->where([
            ['name', '=', 'Supplier Profile Completion'],
        ])->whereHas('baseModule', function($query){
            $query->where('name', 'Account Management');
        })->first();
    }

    /**
     * Create supplier with associated user
     * 
     * @param array $supplierData Supplier data
     * @param array $userData User data (name, email, password, etc.)
     * @return \App\Models\Project\User
     */
    public function createWithUser(array $supplierData, array $userData){
        // Implementing db transaction
        return DB::transaction(function() use($supplierData, $userData){
            // Get base request for supplier profile completion
            $baseRequest = $this->getSupplierProfileCompletionBaseRequest();
            
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

                // Action info
                'created_by'                => auth()->user()->id ?? null,
            ]);

            // Return user with supplier relation loaded
            return $user->load('supplier');
        });
    }

    /**
     * Find supplier profile completion request by token
     * 
     * @param string $token Supplier profile completion token
     * @return \App\Models\Project\UserRequest|null
     */
    public function findSupplierProfileCompletionByToken(string $token){
        // Implementing db transaction
        return DB::transaction(function() use($token){
            // Get base request for supplier profile completion
            $baseRequest = $this->getSupplierProfileCompletionBaseRequest();

            // Get request detail with user, baseRequest, and supplier relations
            // Supplier includes baseQualification, baseBusinessEntity, and baseBank
            $request = UserRequest::with([
                'user', 'supplier.baseQualification', 'supplier.baseBusinessEntity', 'supplier.baseBank',
            ])->where([
                ['base_requests_id', '=', $baseRequest->id],
                ['token', '=', $token],
            ])->first();

            // Return result
            return $request;
        });
    }

    /**
     * Complete supplier profile using token
     * 
     * @param string $token Supplier profile completion token
     * @param array $supplierData Supplier profile data to update
     * @return \App\Models\Project\Supplier
     */
    public function completeSupplierProfile(string $token, array $supplierData){
        return (new FileHelper)->disk()->directory('general')->upload(request()->allFiles());
        
        // Get supplier
        // Supplier

        // Get base request for supplier profile completion
        $baseRequest = $this->getSupplierProfileCompletionBaseRequest();

        // Get request detail with user, baseRequest, and supplier relations
        // Supplier includes baseQualification, baseBusinessEntity, and baseBank
        $request = UserRequest::with([
            'supplier.baseQualification', 'supplier.baseBusinessEntity', 'supplier.baseBank',
        ])->where([
            ['base_requests_id', '=', $baseRequest->id],
            ['token', '=', $token],
        ])->first();

        // Update supplier
        $request->supplier->update([
            'name' => 'Halo hitam',
        ]);

        // // Update base qualification
        // $request->supplier->baseQualification()->update([
        //     'a' => 'a'
        // ]);

        // // Update base business entity
        // $request->supplier->baseBusinessEntity()->update([
        //     'a' => 'a'
        // ]);

        // // Update base bank
        // $request->supplier->baseBank()->update([
        //     'a' => 'a'
        // ]);

        // Refresh supplier with updated relation
        $request->supplier->refresh();
        $request->supplier->load(['baseQualification', 'baseBusinessEntity', 'baseBank']);

        // Return result
        return $request->supplier;

    }
}
