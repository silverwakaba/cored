<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\CookiesHelper;

use Illuminate\Support\Facades\App;

class AppLocaleMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            // Set locale for api backend
            if((request()->routeIs('be.*'))){
                App::setLocale(CookiesHelper::header());
            }

            // Set locale for app
            else{
                App::setLocale(CookiesHelper::appLocale());
            }
        }
        catch(\Throwable $th){
            // no restriction or error message
        }

        return $next($request);
    }
}
