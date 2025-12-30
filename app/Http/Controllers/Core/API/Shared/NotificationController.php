<?php

namespace App\Http\Controllers\Core\API\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\NotificationRepositoryInterface;

// Event
use App\Events\Core\GeneralEventHandler;

// Helper
use App\Helpers\Core\GeneralHelper;

// Request
// use App\Http\Requests\Core\BaseRequestRequest;

// Internal
use Illuminate\Http\Request;

class NotificationController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(NotificationRepositoryInterface $repositoryInterface){
        $this->repositoryInterface = $repositoryInterface;
    }

    // List
    public function list(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Init interface
            $datas = $this->repositoryInterface;

            // Get data based on user id
            $datas->query->where([
                ['users_id', '=', auth()->user()->id],
            ]);

            // Sort data
            $datas->sort([
                'created_at' => 'DESC',
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
            // Create notification
            $datas = $this->repositoryInterface->create([
                'base_requests_id'  => $request->input('request'),
                'users_id'          => $request->input('user'),
                'data'              => $request->input('data'),
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Notification created successfully.',
            ]);
        }, ['status' => 409, 'message' => true]);
    }

    // // Read
    // public function read($id, Request $request){
    //     return GeneralHelper::safe(function() use($id, $request){
    //         // Get base request data
    //         $datas = $this->repositoryInterface;
            
    //         // Load column selection
    //         if(isset($request->select)){
    //             $datas->onlySelect($request->select);
    //         }

    //         // Load relation
    //         if(isset($request->relation)){
    //             $datas->withRelation($request->relation);
    //         }
            
    //         // Continue variable
    //         $datas = $datas->find($id);

    //         // Return response
    //         return GeneralHelper::jsonResponse([
    //             'status'    => 200,
    //             'data'      => $datas,
    //         ]);
    //     }, ['status' => 409, 'message' => false]);
    // }

    // // Update
    // public function update($id, Request $request){
    //     return GeneralHelper::safe(function() use($id, $request){
    //         // Validate input
    //         $validated = GeneralHelper::validate($request->all(), (new BaseRequestRequest())->rules());

    //         // Stop if validation failed
    //         if(!is_array($validated)){
    //             return $validated;
    //         }

    //         // Update base request data
    //         $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'update')->update($id, [
    //             'base_modules_id'   => $request->module,
    //             'name'              => $request->name,
    //             'detail'            => $request->detail,
    //         ]);

    //         // Return response
    //         return GeneralHelper::jsonResponse([
    //             'status'    => 200,
    //             'data'      => $datas,
    //             'message'   => 'Base request updated successfully.',
    //         ]);
    //     }, ['status' => 409, 'message' => true]);
    // }

    // // Delete
    // public function delete($id, Request $request){
    //     return GeneralHelper::safe(function() use($id, $request){
    //         // Delete base request data (actually toggles activation status)
    //         $result = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'delete')->activation($id);

    //         // Get action and data from result
    //         $action = $result['action'];

    //         // Return response
    //         return GeneralHelper::jsonResponse([
    //             'status'    => 200,
    //             'message'   => "Base request {$action} successfully.",
    //         ]);
    //     }, ['status' => 409, 'message' => true]);
    // }
}
