<?php

namespace App\Http\Controllers;

use App\Common\ParamsRules;
use App\Common\Utils;
use App\Library\BLogger;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->params = $_REQUEST;
    }

    public function callAction($method, $parameters)
    {
        $response = call_user_func_array([$this, $method], $parameters);
        BLogger::info($response);
        if (Request::ajax()) {
            return Utils::sendJsonSuccessResponse($response);
        }
        $path_uri = Utils::getPathUri();
        if (isset(ParamsRules::$interface_tpl[$path_uri])) {
            return view(ParamsRules::$interface_tpl[$path_uri], $response);
        }

        return $response;
    }
}
