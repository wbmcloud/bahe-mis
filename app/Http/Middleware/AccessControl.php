<?php

namespace App\Http\Middleware;

use App\Common\Utils;
use App\Exceptions\SlException;
use Closure;
use Illuminate\Support\Facades\Auth;

class AccessControl
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws SlException
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->intended('login');
        }
        $resource_uri = Utils::getPathUri();
        if (!Auth::user()->can($resource_uri)) {
            throw new SlException(SlException::PERMISSION_FAIL_CODE);
        }
        return $next($request);
    }
}
