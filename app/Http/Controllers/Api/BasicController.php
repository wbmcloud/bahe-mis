<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */

namespace App\Http\Controllers\Api;

use App\Common\Constants;
use App\Http\Controllers\Controller;

class BasicController extends Controller
{
    public function cityConfig()
    {
        $city_id = $this->params['city_id'];

        return [
            'city_id' => $city_id,
            'fanxing' => Constants::$city_fanxing[$city_id]
        ];
    }
}