<?php

namespace App\Http\Controllers\API\Core\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\CallToActionRepositoryInterface;

// Helper
use App\Helpers\GeneralHelper;

// Request
use App\Http\Requests\CTAMessageRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CallToActionController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(CallToActionRepositoryInterface $repositoryInterface){
        $this->repositoryInterface = $repositoryInterface;
    }

    // Message
    public function message(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new CTAMessageRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return GeneralHelper::jsonResponse([
                    'status'    => 422,
                    'errors'    => $validator->errors(),
                ]);
            }

            // Send message
            $this->repositoryInterface->messages([
                'name'      => $request->name,
                'email'     => $request->email,
                'subject'   => $request->subject,
                'message'   => $request->message,
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'The Message was sent successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => $th,
            ]);
        }
    }
}
