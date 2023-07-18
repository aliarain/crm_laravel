<?php

namespace App\Http\Middleware;

use App\Repositories\Installer\InitRepository;
use Closure;
use Illuminate\Support\Facades\URL;

class ServiceMiddleware
{
    protected $repo, $service_repo;

    public function __construct(
        InitRepository $service_repo
    ) {
        $this->service_repo = $service_repo;
    }

   /**
 * Handle an incoming request.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \Closure  $next
 * @return mixed
 */
public function handle($request, Closure $next)
{
    // Check if all required files exist
    $allFilesExist = AuthPermitCheck();

    // Get the list of allowed URLs
    $allowedUrls = allowedUrls();

    // Get the current URL
    $currentUrl = URL::current();

    // If the current URL is not in the list of allowed URLs or not all required files exist
    if (!in_array($currentUrl, $allowedUrls) && !$allFilesExist) {
        // If the current URL is not the Install Route, redirect to the Install Route
        if (strpos($currentUrl, URL::route('service.install')) === false) {
            return redirect()->route('service.install');
        }
        if (strpos($currentUrl, URL::route('service.install')) !== false) {
            return $next($request);
        }
        // Otherwise, abort with a 403 error
        abort(403);
    }

    // If the current URL is in the list of allowed URLs and all required files exist, proceed with the request
    return $next($request);
}


}
