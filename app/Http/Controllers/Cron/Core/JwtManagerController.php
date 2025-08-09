<?php

namespace App\Http\Controllers\Cron\Core;
use App\Http\Controllers\Controller;

// Model
use App\Models\User;

// Helper
use App\Helpers\ErrorHelper;

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

            // Refresh token using chunk for better load
            User::whereNotNull('token')->select('id', 'token', 'token_expire_at')->chunkById(50, function(Collection $chunks) use($carbon){
                foreach($chunks as $chunk){
                    $compareData = $carbon->now()->timestamp;
                    
                    // While the remembered, the token valid for 7 days but it will be refreshed every 5 days to avoid any possible errors, given 2 days worth of error margin.
                    if($carbon->parse($chunk->token_expire_at)->subDays(2)->timestamp <= $compareData){
                        // Parse token to the backend to be refreshed / recreated
                        $http = Http::withToken(Crypt::decryptString($chunk->token))->post(
                            route('be.core.auth.jwt.token.create')
                        );

                        // Nullify stored token if failed
                        if(!$http->successful()){
                            $chunk->update([
                                'token'             => null,
                                'token_expire_at'   => null,
                            ]);
                        }

                        // Parse result as json
                        $httpResult = $http->json();

                        // Update stored token if success
                        $chunk->update([
                            'token'             => Crypt::encryptString($httpResult['token']),
                            'token_expire_at'   => $httpResult['token_ttl'],
                        ]);
                    }
                }
            });
        }
        catch(\Throwable $th){
            // throw $th;
        }
    }
}
