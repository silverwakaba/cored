<?php

namespace App\Http\Controllers\Core\API\Auth;
use App\Http\Controllers\Core\Controller;

// Repository interface
use App\Contracts\Core\UserRepositoryInterface;

// Helper
use App\Helpers\Core\ErrorHelper;
use App\Helpers\Core\GeneralHelper;

// Mail
use App\Mail\Core\UserResetPassword;
use App\Mail\Core\UserVerifyEmail;

// Request
use App\Http\Requests\Core\UserAuthLoginRequest;
use App\Http\Requests\Core\UserAuthLostPasswordRequest;
use App\Http\Requests\Core\UserAuthRegisterRequest;
use App\Http\Requests\Core\UserAuthResetPasswordRequest;
use App\Http\Requests\Core\UserAuthVerifyRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

// External
use Carbon\Carbon;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtController extends Controller{
    // Property
    private $userRepository;

    // Constructor
    public function __construct(UserRepositoryInterface $userRepository){
        $this->userRepository = $userRepository;
    }

    // Register
    public function register(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new UserAuthRegisterRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Create registered user
            $datas = $this->userRepository->prepare([
                'name'      => $request['name'],
                'email'     => $request['email'],
                'password'  => bcrypt($request['password']),
            ])->rolePublic()->register();

            // Send email
            try{
                Mail::to($datas['email'])->send(new UserVerifyEmail($datas['id']));
            }
            catch(\Throwable $th){
                // skip invoking the error
            }

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Registration successful.',
            ]);
        });
    }

    // Login
    public function login(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new UserAuthLoginRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Token TTL in minutes
            $tokenTTL = config('jwt.ttl');

            // Get credential input
            $credentials = [
                'email'     => $request['email'],
                'password'  => $request['password'],
            ];

            // Attempt auth
            if(!$token = auth()->guard('api')->setTTL($tokenTTL)->attempt(
                array_merge($credentials, ['is_active' => true])
            )){
                // Error message
                return response()->json([
                    'success'   => false,
                    'errors'    => [
                        'password' => 'Password is not recognized.',
                    ],
                    'message'   => 'Authentication failed.',
                ], 401);
            }

            // Get user information
            $user = auth()->guard('api')->user();

            // Get unix timestamp
            $timestamp = Carbon::now()->addMinutes($tokenTTL)->toIso8601String();

            // Store token inside database if user want the session to be remembered
            // It will then be renewed periodically in the background via cron
            if(($request->remember == true)){
                // Update token
                $this->userRepository->update($user->id, [
                    'token'             => Crypt::encryptString($token), // encrypt the token
                    'token_expire_at'   => $timestamp,
                ]);
            }

            // Return response
            return response()->json([
                'success'   => true,
                'data'      => $user,
                'token'     => $token,
                'token_ttl' => $timestamp,
                'message'   => 'Session started successfully.',
            ], 200);
        });
    }

    // Validate token
    public function validateToken(){
        return GeneralHelper::safe(function(){
            // Get JWT token
            $token = JWTAuth::getToken();

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'Authorized token.',
            ]);
        }, ['status' => 409, 'message' => true]);
    }

    // Create token
    public function createToken(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Get JWT token
            $token = JWTAuth::getToken();

            // Refresh JWT token
            $newToken = JWTAuth::refresh($token);

            // Token TTL in minutes
            $tokenTTL = config('jwt.ttl');

            // Get unix timestamp
            $timestamp = Carbon::now()->addMinutes($tokenTTL)->toDateTimeString();

            // Return response
            return response()->json([
                'success'   => true,
                'token'     => $newToken,
                'token_ttl' => $timestamp,
                'message'   => 'Token refreshed successfully.',
            ], 200);
        });
    }

    // Logout
    public function logout(){
        return GeneralHelper::safe(function(){
            // Get JWT token
            $token = JWTAuth::getToken();
            
            // Invalidate JWT token
            $invalidateToken = JWTAuth::invalidate($token);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'Authentication revoked successfully.',
            ]);
        });
    }

    // Verify account
    public function verifyAccount(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new UserAuthVerifyRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Search user and it's eligibility
            $eligibility = $this->userRepository->search([
                'id'    => $request['id'],
                'email' => $request['email'],
            ])->requestEligibility(1)->getData(true); // get data as array

            // If account is not found and/or not eligible
            if($eligibility['success'] == false){
                // Return response
                return GeneralHelper::jsonResponse([
                    'status'    => 404,
                    'message'   => 'This account is not eligible for this action. Please try again later.',
                ]);
            } else {
                // Send email
                try{
                    Mail::to($request['email'])->send(new UserVerifyEmail($eligibility['data']['id']));
                }
                catch(\Throwable $th){
                    // skip invoking the error
                }
            }

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'The account is found to be eligible for this action. Please check your email for more information.',
            ]);
        });
    }

    // Verify account via token
    public function verifyAccountTokenized($token){
        return GeneralHelper::safe(function() use($token){
            // Verify account
            $datas = $this->userRepository->verifyAccount($token);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Account verification successful.',
            ]);
        });
    }

    // Reset password
    public function resetPassword(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input (this have same request rules as verification)
            $validated = GeneralHelper::validate($request->all(), (new UserAuthVerifyRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Search user and it's eligibility
            $eligibility = $this->userRepository->search([
                'id'    => $request['id'],
                'email' => $request['email'],
            ])->requestEligibility(3)->getData(true); // get data as array

            // If account is not found and/or not eligible
            if($eligibility['success'] == false){
                // Return response
                return GeneralHelper::jsonResponse([
                    'status'    => 404,
                    'message'   => 'This account is not eligible for this action. Please try again later.',
                ]);
            } else {
                // Send email
                try{
                    Mail::to($request['email'])->send(new UserResetPassword($eligibility['data']['id']));
                }
                catch(\Throwable $th){
                    // skip invoking the error
                }
            }

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'The account is found to be eligible for this action. Please check your email for more information.',
            ]);
        }, ['status' => 409, 'message' => true]);
    }

    // Reset password
    public function resetPasswordTokenized($token, Request $request){
        return GeneralHelper::safe(function() use($token, $request){
            // Validate input (this have same request rules as verification)
            $validated = GeneralHelper::validate($request->all(), (new UserAuthResetPasswordRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Search user and it's eligibility
            $datas = $this->userRepository->resetPassword([
                'token'         => $token,
                'new_password'  => $request['new_password'],
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'Password has been successfully reset',
            ]);
        });
    }
}
