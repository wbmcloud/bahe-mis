<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiBaseController extends Controller
{

    protected function sendJsonResponse($code, $message, array $data)
    {
        $ret['code'] = $code;
        $ret['message'] = $message;
        $ret['data'] = $data;
        return response()->json($ret);
    }
    
}