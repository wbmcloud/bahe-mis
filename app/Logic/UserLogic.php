<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Common\Constants;
use App\Models\City;

class UserLogic extends BaseLogic
{
    public function getAllOpenCities()
    {
        $cities = City::where('status', Constants::COMMON_ENABLE)->get();
        return $cities;
    }
}