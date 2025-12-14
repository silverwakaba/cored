<?php

namespace App\Http\Controllers\Core\Cron;
use App\Http\Controllers\Core\Controller;

// Model
use App\Models\Core\User;

// Helper
use App\Helpers\Core\ErrorHelper;

// Internal
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

// External
use Carbon\Carbon;

class JwtManagerController extends Controller{
    // Refresh stored token
    public static function refresh(){
        try{
            // Include carbon
            $carbon = new Carbon();
            $now = $carbon->now();
            
            // Refresh threshold: refresh token 15 minutes before it expires
            // This gives enough time margin for short-lived tokens (1 hour)
            // Tokens will be refreshed when they have 15 minutes or less remaining
            $refreshThreshold = $now->copy()->addMinutes(15);
            
            // Also cleanup expired tokens (within last 7 days to avoid processing very old records)
            $expiredLimit = $now->copy()->subDays(7);
            
            // Optimize query: only fetch tokens that need processing
            // 1. Tokens expiring within 15 minutes (need refresh)
            // 2. Expired tokens (need cleanup, but limit to last 7 days)
            // This significantly reduces database load when scheduler runs frequently
            User::whereNotNull('token')->whereNotNull('token_expire_at')->where(function($query) use($now, $refreshThreshold, $expiredLimit){
                    $query
                        // Tokens that need refresh (expires within 15 minutes from now)
                        ->whereBetween('token_expire_at', [$now->toDateTimeString(), $refreshThreshold->toDateTimeString()])
                        
                        // OR expired tokens that need cleanup (expired within last 7 days)
                        ->orWhere(function($q) use($now, $expiredLimit){
                            $q->where('token_expire_at', '<=', $now->toDateTimeString())->where('token_expire_at', '>=', $expiredLimit->toDateTimeString());
                        });
                })->select('id', 'token', 'token_expire_at')->chunkById(50, function(Collection $chunks) use($carbon, $now, $refreshThreshold){
                    foreach($chunks as $chunk){
                        try{
                            // Skip if token_expire_at is null or invalid
                            if(!$chunk->token_expire_at){
                                continue;
                            }

                            // Parse token expiration time
                            $tokenExpireAt = $carbon->parse($chunk->token_expire_at);
                            
                            // Clean up expired tokens
                            if($tokenExpireAt->isPast()){
                                $chunk->update([
                                    'token'             => null,
                                    'token_expire_at'   => null,
                                ]);

                                continue;
                            }
                            
                            // Only refresh if token expires within the threshold (15 minutes)
                            // This ensures we refresh before expiration while avoiding unnecessary refreshes
                            if($tokenExpireAt->lte($refreshThreshold)){
                                // Decrypt and use token for refresh request
                                $decryptedToken = Crypt::decryptString($chunk->token);
                                
                                // Get route URL - ensure it's absolute URL for console context
                                try {
                                    $routeUrl = route('be.core.auth.jwt.token.create');
                                    // If route returns relative URL, make it absolute
                                    if(!filter_var($routeUrl, FILTER_VALIDATE_URL)){
                                        $baseUrl = config('app.url', 'http://localhost');
                                        $routeUrl = rtrim($baseUrl, '/') . '/' . ltrim($routeUrl, '/');
                                    }
                                } catch(\Exception $e) {
                                    // Fallback: construct URL manually
                                    $baseUrl = config('app.url', 'http://localhost');
                                    $routeUrl = rtrim($baseUrl, '/') . '/api/be/auth/jwt/token/create';
                                }
                            
                                // Request new token from backend
                                $http = Http::withToken($decryptedToken)
                                    ->timeout(10) // 10 seconds timeout
                                    ->post($routeUrl);

                                // Handle failed request
                                if(!$http->successful()){
                                    // If token is invalid/expired, clean up
                                    // Otherwise, keep existing token for retry on next run
                                    if($http->status() === 401 || $http->status() === 403){
                                        $chunk->update([
                                            'token'             => null,
                                            'token_expire_at'   => null,
                                        ]);
                                    }
                                    
                                    continue;
                                }

                                // Parse result as json
                                $httpResult = $http->json();

                                // Validate response structure
                                if(!isset($httpResult['token']) || !isset($httpResult['token_ttl'])){
                                    continue;
                                }

                                // Update stored token with new token and expiration
                                $chunk->update([
                                    'token'             => Crypt::encryptString($httpResult['token']),
                                    'token_expire_at'   => $httpResult['token_ttl'],
                                ]);
                            }
                        }
                        catch(\Illuminate\Contracts\Encryption\DecryptException $e){
                            // Token decryption failed - clean up invalid token
                            $chunk->update([
                                'token'             => null,
                                'token_expire_at'   => null,
                            ]);
                        }
                        catch(\Throwable $e){
                            // Continue processing other users
                            continue;
                        }
                    }
                });
        }
        catch(\Throwable $th){
            // Re-throw to let Laravel handle it
            throw $th;
        }
    }
}
