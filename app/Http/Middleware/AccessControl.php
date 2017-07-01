<?php

namespace App\Http\Middleware;

use App\Common\Constants;
use App\Common\ParamsRules;
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
        $user = Auth::user();
        // 代理协议
        if (!Auth::user()->hasRole([Constants::ROLE_SUPER, Constants::ROLE_ADMIN]) &&
            !in_array($resource_uri, Constants::$agreement_uri)) {
            if (!$user->is_accept) {
                return redirect(ParamsRules::IF_USER_AGREEMENT);
            }
        }

        if (!$user->can($resource_uri)) {
            throw new SlException(SlException::PERMISSION_FAIL_CODE);
        }

        return $next($request);
    }
}
