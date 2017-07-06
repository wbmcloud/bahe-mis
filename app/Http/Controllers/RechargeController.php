<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/11
 * Time: 下午10:55
 */

namespace App\Http\Controllers;

use App\Logic\AccountLogic;
use App\Logic\RechargeLogic;

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

        return [
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