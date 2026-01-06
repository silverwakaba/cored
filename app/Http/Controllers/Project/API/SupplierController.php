<?php

namespace App\Http\Controllers\Project\API;
use App\Http\Controllers\Controller;

// // Repository interface
use App\Contracts\Project\SupplierRepositoryInterface;

// Helper
use App\Helpers\Core\GeneralHelper;

// Request
use App\Http\Requests\Project\SupplierRequest;

// Internal
use Illuminate\Http\Request;

class SupplierController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(SupplierRepositoryInterface $repositoryInterface){
        $this->repositoryInterface = $repositoryInterface;
    }

    // List
    public function list(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Get data
            $datas = $this->repositoryInterface;

            // Sort data
            $datas->sort([
                'name' => 'ASC',
            ]);

            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }

            // Return response
            return ($request->type === 'datatable') ? $datas->useDatatable()->all() : $datas->all();
        }, ['status' => 409, 'message' => false]);
    }

    // Create
    public function create(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new SupplierRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Create new supplier and it's PIC
            $datas = $this->repositoryInterface->createWithUser(
                // Supplier
                [
                    // Foreign keys
                    'base_qualification_id'     => $request->base_qualification_id,     // 363: Kecil
                    'base_business_entity_id'   => $request->base_business_entity_id,   // 265: PT
                    'base_bank_id'              => $request->base_bank_id,
                    
                    // Basic information
                    'code'                      => $request->code,
                    'name'                      => $request->name,
                    'credit_day'                => $request->credit_day,
                    
                    // Address
                    'address_1'                 => $request->address_1,
                    'address_2'                 => $request->address_2,
                    
                    // Contact
                    'telp'                      => $request->telp,
                    'fax'                       => $request->fax,
                    
                    // Tax information
                    'npwp'                      => $request->npwp,
                    'npwp_address'              => $request->npwp_address,
                    
                    // Bank information
                    'bank_account_name'         => $request->bank_account_name,
                    'bank_account_number'       => $request->bank_account_number,
                    
                    // Additional information
                    'pkp'                       => $request->pkp,
                    'nib'                       => $request->nib,
                    'notes'                     => $request->notes,
                    // 'statement_file_path'       => $request->statement_file_path,
                ],

                // User
                [
                    'name'      => $request->pic_name,
                    'email'     => $request->pic_email,
                    'password'  => bcrypt(now()), // Temporary password, should be reset
                    'created_by' => auth()->user()->id ?? null,
                    'updated_by' => auth()->user()->id ?? null,
                ],
                
                // Role to assign
                'Supplier'
            );

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Supplier created successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Read
    public function read($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Get base module data
            $datas = $this->repositoryInterface;
            
            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }
            
            // Continue variable
            $datas = $datas->find($id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // // Update
    // public function update($id, Request $request){
    //     return GeneralHelper::safe(function() use($id, $request){
    //         // Validate input
    //         $validated = GeneralHelper::validate($request->all(), (new BaseModuleRequest())->rules());

    //         // Stop if validation failed
    //         if(!is_array($validated)){
    //             return $validated;
    //         }

    //         // Update base module data
    //         $datas = $this->repositoryInterface->update($id, [
    //             'name' => $request->name,
    //         ]);

    //         // Return response
    //         return GeneralHelper::jsonResponse([
    //             'status'    => 200,
    //             'data'      => $datas,
    //             'message'   => 'Base module updated successfully.',
    //         ]);
    //     }, ['status' => 409, 'message' => false]);
    // }

    // // Delete
    // public function delete($id, Request $request){
    //     return GeneralHelper::safe(function() use($id, $request){
    //         // Delete base module data (actually toggles activation status)
    //         $result = $this->repositoryInterface->activation($id);

    //         // Get action and data from result
    //         $action = $result['action'];

    //         // Return response
    //         return GeneralHelper::jsonResponse([
    //             'status'    => 200,
    //             'message'   => "Base module {$action} successfully.",
    //         ]);
    //     }, ['status' => 409, 'message' => false]);
    // }

    // // Bulk Delete
    // public function bulkDestroy(Request $request){
    //     return GeneralHelper::safe(function() use($request){
    //         // Validate input
    //         $ids = $request->input('ids', []);
            
    //         // Check if ids is provided and is array
    //         if(empty($ids) || !is_array($ids)){
    //             return GeneralHelper::jsonResponse([
    //                 'status'    => 400,
    //                 'message'   => 'No data selected.',
    //             ], 400);
    //         }

    //         // Delete base module data (actually toggles activation status)
    //         $result = $this->repositoryInterface->activation($ids);

    //         // Get action and data from result
    //         $action = $result['action'];
    //         $count = $result['data'];

    //         // Return response
    //         return GeneralHelper::jsonResponse([
    //             'status'    => 200,
    //             'message'   => "{$count} base module(s) {$action} successfully.",
    //         ]);
    //     }, ['status' => 409, 'message' => false]);
    // }
}
