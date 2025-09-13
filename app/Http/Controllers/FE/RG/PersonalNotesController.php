<?php

namespace App\Http\Controllers\FE\RG;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\ApiRepositoryInterface;

// Helper
use App\Helpers\ErrorHelper;

// Internal
use Illuminate\Http\Request;

class PersonalNotesController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Index
    public function index(){
        return view('pages/app/rg/personal-notes/index');
    }

    // Reader
    public function reader(){
        return view('pages/notes');
    }

    // List
    public function list(){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.rg.notes.list', array_merge(
            request()->all(), [
                'type' => request()->type,
            ])
        );

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Create
    public function create(Request $request){
        // Update permission
        $http = $this->apiRepository->withToken()->post('be.rg.notes.create', [
            'is_public' => (bool) $request->is_public,
            'title'     => $request->title,
            'content'   => $request->content,
            'user_sync' => $request->user_sync,
        ]);
        
        // Response for $update action
        return response()->json($http->json(), $http->status());
    }

    // Read
    public function read($id){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.rg.notes.read', [
            'id' => $id,
        ]);

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Update
    public function update($id, Request $request){
        // Update permission
        $http = $this->apiRepository->withToken()->post('be.rg.notes.update', [
            'id'        => $id,
            'is_public' => (bool) $request->is_public,
            'title'     => $request->title,
            'content'   => $request->content,
            'user_sync' => $request->user_sync,
        ]);
        
        // Response for $update action
        return response()->json($http->json(), $http->status());
    }

    // Delete
    public function delete($id, Request $request){
        // Delete permission
        $http = $this->apiRepository->withToken()->post('be.rg.notes.delete', [
            'id' => $id,
        ]);
        
        // Response for $delete action
        return response()->json($http->json(), $http->status());
    }
}
