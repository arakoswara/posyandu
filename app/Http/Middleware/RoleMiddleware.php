<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roleName)
    {
        if (Auth::check()) {
            
            if (Auth::check() && !Auth::user()->hasRole($roleName)) {
                
                return abort(401, 'Anda tidak berhak mengakses halaman ini, ini halaman visitor');

            }
            return $next($request);
        
        }else{
            
            return abort(404, 'Aborted');
        }
    }
}
