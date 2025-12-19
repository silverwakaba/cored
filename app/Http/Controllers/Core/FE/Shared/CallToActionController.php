<?php

namespace App\Http\Controllers\Core\FE\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\ApiRepositoryInterface;

// Helper
use App\Helpers\Core\ErrorHelper;

// Internal
use Illuminate\Http\Request;

class CallToActionController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Message
    public function message(){
        return view('pages/cta/message');
    }

    public function messagePost(Request $request){
        // Create permission
        $http = $this->apiRepository->withToken()->withAttachment()->post('be.core.cta.message', [
            'name'                  => $request->name,
            'email'                 => $request->email,
            'subject'               => $request->subject,
            'message'               => $request->message,
            'agreement'             => $request->agreement,
            'h-captcha-response'    => $request->input('h-captcha-response'),
        ]);
        
        // Response
        return response()->json($http->json(), $http->status());
    }
}
