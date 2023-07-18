<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TimeZone
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
        if (auth()->check()) {
            // $time_zone=auth()->user()->country->time_zone;
            $time_zone=settings('timezone')??config('app.timezone');
            config(['app.timezone' => $time_zone]);
            date_default_timezone_set($time_zone);
         }
        return $next($request);
    }
}
