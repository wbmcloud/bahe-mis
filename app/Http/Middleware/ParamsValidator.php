<?php

namespace App\Http\Middleware;

use App\Common\ParamsRules;
use App\Common\Utils;
use Closure;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ParamsValidator
{
    use ValidatesRequests;

    public static $messages = [
        'required'    => 'The :attribute field is required.',
        'digits'      => 'The :attribute field is not valid.',
        'integer'     => 'The :attribute field is not valid.',
        'date_format' => 'The :attribute field is not valid.',
        'string'      => 'The :attribute field is not valid.',
        'in'          => 'The :attribute must be one of the following types: :values',
        'size'        => 'The :attribute must be exactly :size.',
        'between'     => 'The :attribute must be between :min - :max.',
        'max'         => 'The :attribute must less than :max.',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $resource_uri = Utils::getPathUri();
        if (isset(ParamsRules::$rules[$resource_uri])) {
            $this->validate($request, ParamsRules::$rules[$resource_uri], self::$messages);
        }

        return $next($request);
    }
}
