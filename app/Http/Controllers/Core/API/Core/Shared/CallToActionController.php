<?php

namespace App\Http\Controllers\Core\API\Core\Shared;
use App\Http\Controllers\Core\Controller;

// Repository interface
use App\Contracts\Core\CallToActionRepositoryInterface;

// Helper
use App\Helpers\Core\FileHelper;
use App\Helpers\Core\GeneralHelper;

// Request
use App\Http\Requests\Core\CTAMessageRequest;

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
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new CTAMessageRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
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
        }, ['status' => 409, 'message' => false]);
    }
}
