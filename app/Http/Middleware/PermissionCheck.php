<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (Auth::check() && in_array($permission, Auth::user()->permissions)) :
            return $next($request);
        elseif (Auth::check() && config('app.APP_BRANCH') != 'saas' && @auth()->user()->is_admin == 1) :
            return $next($request);
        endif;
        return abort(403, 'Access Denied');
    }
}
