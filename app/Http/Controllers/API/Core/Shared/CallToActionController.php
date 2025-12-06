<?php

namespace App\Http\Controllers\API\Core\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\CallToActionRepositoryInterface;

// Helper
use App\Helpers\FileHelper;
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

            // Default form submission
            $message = [
                'name'      => $request->name,
                'email'     => $request->email,
                'subject'   => $request->subject,
                'message'   => $request->message,
            ];

            // Check attachment
            $check_attachment = $request->hasFile('attachment');

            // Check auth
            $check_auth = auth()->guard('api')->user();

            // Handle attachment if user is authenticated and upload an attachment
            if($check_attachment && $check_auth){
                // Handle attachment
                $attachment = (new FileHelper)->disk()->directory('cta/message')->upload($request->allFiles());

                // Normalisasi key attachment agar tersimpan rapi di kolom JSON
                $message['attachment'] = array_values($attachment);
            }

            // Add user info to the message
            $message['users_id'] = $check_auth['id'] ?? null;

            // Send message
            $this->repositoryInterface->messages($message);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'The message was sent successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }
}
