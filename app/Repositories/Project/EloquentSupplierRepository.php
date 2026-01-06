<?php

namespace App\Repositories\Project;

// Model
use App\Models\Core\User;
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
     * @param string|null $role Role to assign to user (default: 'Supplier')
     * @return \App\Models\Project\Supplier
     */
    public function createWithUser(array $supplierData, array $userData, ?string $role = 'Supplier'){
        // Implementing db transaction
        return DB::transaction(function() use($supplierData, $userData, $role){
            // // Create user
            $user = User::create($userData);

            // // Assign role to user
            // if($role){
            //     $user->assignRole($role);
            // }

            // // Link supplier to user
            // $supplierData['users_id'] = $user->id;

            // // Add additional data to supplier (e.g., created_by, updated_by, etc.)
            // $supplierData = array_merge($supplierData, [
            //     'created_by' => auth()->user()->id ?? null,
            //     'updated_by' => auth()->user()->id ?? null,
            // ]);

            // // Create supplier
            // $supplier = $this->create($supplierData);

            // Return supplier with user relation loaded
            // return $supplier->load('user');
        });
    }
}
