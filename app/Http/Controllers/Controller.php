<?php

namespace App\Http\Controllers;

use App\Common\ParamsRules;
use App\Common\Utils;
use App\Events\ActionEvent;
use App\Library\BLogger;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
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
        if (is_array($response)) {
            BLogger::info($response);
        }
        $path_uri = Utils::getPathUri();

        // 记录操作日志
        if (in_array($path_uri, ParamsRules::$action_interface)) {
            event(new ActionEvent(Auth::user(), \Illuminate\Http\Request::createFromGlobals()));
        }

        // AJAX请求
        if (Request::ajax()) {
            return Utils::sendJsonSuccessResponse($response);
        }
        // 渲染模板
        if (isset(ParamsRules::$interface_tpl[$path_uri])) {
            if ($response instanceof RedirectResponse) {
                return $response;
            }
            return view(ParamsRules::$interface_tpl[$path_uri], $response);
        }

        return $response;
    }
}
