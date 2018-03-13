<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/11
 * Time: 下午10:55
 */

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Logic\AccountLogic;
use App\Logic\RechargeLogic;
use App\Logic\UserLogic;
use Illuminate\Support\Facades\Auth;

class RechargeController extends Controller
{
    public function showAgentRechargeForm()
    {
        return [];
    }

    public function showUserRechargeForm()
    {
        $account_logic = new AccountLogic();
        $account      = $account_logic->getAgentBalance();

        $user = Auth::user();
        if ($user->hasRole(Constants::$admin_role)) {
            // 管理员和超级管理员
            $user_logic = new UserLogic();
            $cities     = $user_logic->getOpenCities();

            return [
                'agent'  => $user,
                'cities' => $cities,
            ];
        }

        return [
            'agent'  => $user,
            'account' => $account
        ];
    }

    public function agentRecharge()
    {
        $recharge_logic = new RechargeLogic();

        return $recharge_logic->agentRecharge($this->params);
    }

    public function userRecharge()
    {
        $recharge_logic = new RechargeLogic();

        return $recharge_logic->userRecharge($this->params);
    }
}