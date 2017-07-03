<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Logic\UserLogic;

class UserController extends Controller
{
    public function addUserForm()
    {
        switch ($this->params['type']) {
            case Constants::ADD_USER_TYPE_ADMIN:
                return view('auth.add_admin');
                break;
            case Constants::ADD_USER_TYPE_AGENT:
                $user_logic = new UserLogic();
                $cities     = $user_logic->getOpenCities();
                return view('auth.add_agent', ['cities' => $cities]);
                break;
            case Constants::ADD_USER_TYPE_FIRST_AGENT:
                $user_logic = new UserLogic();
                $cities     = $user_logic->getOpenCities();
                return view('auth.add_first_agent', ['cities' => $cities]);
                break;

            default:
                return view('auth.add_admin');
        }
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