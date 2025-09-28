<?php

namespace App\Http\Controllers\API\Core\Auth;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\UserRepositoryInterface;

// Helper
use App\Helpers\ErrorHelper;

// Request
use App\Http\Requests\UserAuthLoginRequest;
use App\Http\Requests\UserAuthLostPasswordRequest;
use App\Http\Requests\UserAuthRegisterRequest;
use App\Http\Requests\UserAuthResetPasswordRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new UserAuthRegisterRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors(),
                ], 422);
            }

            // Create registered user
            $datas = $this->userRepository->prepare([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password),
            ])->role('User')->register();

            // Return created user
            return response()->json([
                'success'   => true,
                'data'      => $datas,
                'message'   => "Registration successful.",
            ], 201);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Login
    public function login(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new UserAuthLoginRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors(),
                ], 422);
            }

            // Token TTL in minutes
            $tokenTTL = config('jwt.ttl');

            // Get credential input
            $credentials = $request->only('email', 'password');

            // Attempt auth
            if(!$token = auth()->guard('api')->setTTL($tokenTTL)->attempt(
                array_merge($credentials, ['is_active' => true])
            )){
                // Error message
                return response()->json([
                    'success'   => false,
                    'errors'    => [
                        'password' => "Password is not recognized.",
                    ],
                    'message'   => "Authentication failed.",
                ], 401);
            }

            // Get user information
            $user = auth()->guard('api')->user();

            // Get unix timestamp
            $timestamp = Carbon::now()->addMinutes($tokenTTL)->toIso8601String();

            // Store token inside databasse if user want the session to be remembered
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
                'message'   => "Session started successfully.",
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Validate token
    public function validateToken(){
        try{
            // Get JWT token
            $token = JWTAuth::getToken();

            // Return response
            return response()->json([
                'success'   => true,
                'message'   => "Authorized token.",
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Create token
    public function createToken(Request $request){
        try{
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
                'message'   => "Token refreshed successfully."
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Logout
    public function logout(){
        try{
            // Get JWT token
            $token = JWTAuth::getToken();
            
            // Invalidate JWT token
            $invalidateToken = JWTAuth::invalidate($token);

            // Return response
            return response()->json([
                'success'   => true,
                'message'   => "Authentication revoked successfully.",
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }
}
