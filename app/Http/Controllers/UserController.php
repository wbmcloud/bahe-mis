<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */

namespace App\Http\Controllers;

use App\Logic\UserLogic;

class UserController extends Controller
{
    public function addUserForm()
    {
        $user_logic = new UserLogic();
        $cities     = $user_logic->getOpenCities();

        return [
            'cities' => $cities
        ];
    }

    public function addResetPasswordForm()
    {
        return [];
    }

    public function add()
    {
        $user_logic = new UserLogic();

        return $user_logic->add($this->params);
    }

    public function reset()
    {
        $user_logic = new UserLogic();

        return $user_logic->reset($this->params);
    }

    public function agree()
    {
        $user_logic = new UserLogic();

        return $user_logic->agree($this->params);
    }

}