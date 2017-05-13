<?php

namespace App\Http\Middleware;

use App\Exceptions\SlException;
use Closure;
use Illuminate\Support\Facades\Auth;

class AccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->intended('login');
        }
        $resource_uri = preg_replace('/\?.*/', '', $request->getRequestUri());
        if (!Auth::user()->can($resource_uri)) {
            return response()->view('error',['message' => SlException::$error_msg[SlException::PERMISSION_FAIL_CODE]]);
        }
        return $next($request);
    }
}
