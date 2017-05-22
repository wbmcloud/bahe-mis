<?php

namespace App\Http\Middleware;

use App\Common\ParamsRules;
use Closure;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ParamsValidator
{
    use ValidatesRequests;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $resource_uri = preg_replace('/\?.*/', '', $request->getRequestUri());
        if (isset(ParamsRules::$rules[$resource_uri])) {
            $this->validate($request, ParamsRules::$rules[$resource_uri]);
        }

        return $next($request);
    }
}
