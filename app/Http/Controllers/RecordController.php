<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/11
 * Time: 下午10:55
 */

namespace App\Http\Controllers;

use App\Logic\AgentLogic;
use App\Logic\RechargeLogic;
use Carbon\Carbon;

class RecordController extends Controller
{
    public function agentRecharge()
    {
        $recharge_logic = new RechargeLogic();

        $start_time = Carbon::now()->subMonth()->toDateTimeString();
        $end_time = Carbon::now()->toDateTimeString();

        return [
            'recharge_list' => $recharge_logic->agentRechargeRecord($this->params, $start_time, $end_time)
        ];
    }

    public function userRecharge()
    {
        $recharge_logic = new RechargeLogic();

        $start_time = Carbon::now()->subMonth()->toDateTimeString();
        $end_time = Carbon::now()->toDateTimeString();

        return [
            'recharge_list' => $recharge_logic->userRechargeRecord($this->params, $start_time, $end_time)
        ];
    }

    public function openRoom()
    {
        $agent_logic = new AgentLogic();

        $start_time = Carbon::now()->subMonth()->toDateTimeString();
        $end_time = Carbon::now()->toDateTimeString();

        return [
            'recharge_list' => $agent_logic->openRoomRecord($this->params, $start_time, $end_time)
        ];
    }

}