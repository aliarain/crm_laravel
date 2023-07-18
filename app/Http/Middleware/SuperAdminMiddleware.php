<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Config;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (@Auth::user()->role_id==1)
            {
                return $next($request);
            }elseif(@Auth::user()->id==2 && config('app.APP_BRANCH')=='saas') {
                return $next($request);
            }else {
                abort('401');
            }
         } else {
             return redirect('/');
         }
    }
}
