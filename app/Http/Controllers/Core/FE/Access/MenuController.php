<?php

namespace App\Http\Controllers\Core\FE\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\ApiRepositoryInterface;

// Internal
use Illuminate\Http\Request;

class MenuController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Index
    public function index(){
        return view('core.pages.app.menu.index');
    }

    // List
    public function list(){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.menu.list', array_merge(
            request()->all(), [
                'type'      => request()->type,
                'relation'  => ['parent:id,name', 'children:id,name,order'],
            ])
        );

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Create
    public function create(Request $request){
        // Create menu (form field names: parent, authenticate, guest_only)
        $http = $this->apiRepository->withToken()->post('be.core.menu.store', [
            'name'              => $request->name,
            'icon'              => $request->icon,
            'route'             => $request->route,
            'type'              => $request->type,
            'parent'            => $request->parent,
            'authenticate'      => $request->authenticate,
            'guest_only'        => $request->guest_only,
            'position'          => $request->position,
            'reference_id'      => $request->reference_id,
        ]);
        
        // Response for $create action
        return response()->json($http->json(), $http->status());
    }

    // Read
    public function read($id){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.menu.show', [
            'id'        => $id,
            'relation'  => ['parent:id,name', 'children:id,name,order'],
        ]);

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Update
    public function update($id, Request $request){
        // Update menu (form field names: parent, authenticate, guest_only)
        $http = $this->apiRepository->withToken()->put('be.core.menu.update', [
            'id'                => $id,
            'name'              => $request->name,
            'icon'              => $request->icon,
            'route'             => $request->route,
            'type'              => $request->type,
            'parent'            => $request->parent,
            'authenticate'      => $request->authenticate,
            'guest_only'        => $request->guest_only,
        ]);
        
        // Response for $update action
        return response()->json($http->json(), $http->status());
    }

    // Update Position
    public function updatePosition($id, Request $request){
        // Update menu position
        $http = $this->apiRepository->withToken()->put('be.core.menu.update_position', [
            'id'            => $id,
            'position'      => $request->position,
            'reference_id'  => $request->reference_id,
        ]);
        
        // Response for $update action
        return response()->json($http->json(), $http->status());
    }

    // Delete
    public function delete($id, Request $request){
        // Delete menu
        $http = $this->apiRepository->withToken()->delete('be.core.menu.destroy', [
            'id' => $id,
        ]);
        
        // Response for $delete action
        return response()->json($http->json(), $http->status());
    }
}


