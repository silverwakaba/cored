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
     * Find user request by base request and token with relations
     * 
     * @param \App\Models\Core\BaseRequest $baseRequest
     * @param string $token
     * @return \App\Models\Project\UserRequest|null
     */
    private function findUserRequestByBaseRequestAndToken($baseRequest, string $token){
        return UserRequest::with([
            'user', 'supplier.baseQualification', 'supplier.baseBusinessEntity', 'supplier.baseBank',
        ])->where([
            ['base_requests_id', '=', $baseRequest->id],
            ['token', '=', $token],
        ])->first();
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
            $request = $this->findUserRequestByBaseRequestAndToken($baseRequest, $token);

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
    public function completeSupplierProfile(string $token, array $supplierData, array $userData){
        // Implementing db transaction
        return DB::transaction(function() use($token, $supplierData, $userData){
            // Get base request for supplier profile completion
            $baseRequest = $this->getSupplierProfileCompletionBaseRequest();

            // Get request detail with user, baseRequest, and supplier relations
            // Supplier includes baseQualification, baseBusinessEntity, and baseBank
            $request = $this->findUserRequestByBaseRequestAndToken($baseRequest, $token);

            // Check if token is not found
            if(!$request){
                throw new \Exception('Token not found or invalid.');
            }

            // Delete old statement if exist
            (new FileHelper)->disk()->delete($request['supplier']['statement_file_path']);

            // Update supplier
            $request->supplier->update([
                // Foreign keys
                'base_qualification_id'     => $supplierData['base_qualification_id'],     // 363
                'base_business_entity_id'   => $supplierData['base_business_entity_id'],   // 265
                'base_bank_id'              => $supplierData['base_bank_id'],              // 268
                
                // Supplier
                'name'                      => $supplierData['name'],
                'address_1'                 => $supplierData['address_1'],
                'address_2'                 => $supplierData['address_2'],
                'telp'                      => $supplierData['telp'],
                'fax'                       => $supplierData['fax'],
                'npwp'                      => $supplierData['npwp'],
                'npwp_address'              => $supplierData['npwp_address'],
                'bank_account_name'         => $supplierData['bank_account_name'],
                'bank_account_number'       => $supplierData['bank_account_number'],
                'pkp'                       => $supplierData['pkp'],
                'nib'                       => $supplierData['nib'],
                'notes'                     => $supplierData['notes'],
                'statement_file_path'       => $supplierData['statement_file_path'],
                'is_active'                 => true,
            ]);

            // Update user
            $request->user->update([
                'name'      => $userData['pic_name'],
                'password'  => bcrypt($userData['pic_password']),
            ]);

            // Delete supplier profile completion token
            // $request->delete();

            // Refresh supplier with updated relation
            $request->supplier->refresh();
            $request->supplier->load(['user', 'baseQualification', 'baseBusinessEntity', 'baseBank']);

            // Return result
            return $request->supplier;
        });
    }
}
