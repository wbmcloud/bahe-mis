<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/11
 * Time: 下午10:55
 */

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Logic\AgentLogic;
use App\Logic\RechargeLogic;
use App\Logic\UserBindLogic;
use App\Models\UserBindPlayer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    public function agentRecharge()
    {
        $recharge_logic = new RechargeLogic();

        $start_time = Carbon::now()->subMonth()->toDateTimeString();
        $end_time = Carbon::now()->toDateTimeString();
        $this->params['page_size'] = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        return [
            'recharge_list' => $recharge_logic->agentRechargeRecord($this->params, $start_time, $end_time)
        ];
    }

    public function userRecharge()
    {
        $recharge_logic = new RechargeLogic();

        $start_time = Carbon::now()->subMonth()->toDateTimeString();
        $end_time = Carbon::now()->toDateTimeString();
        $this->params['page_size'] = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        return [
            'recharge_list' => $recharge_logic->userRechargeRecord($this->params, $start_time, $end_time)
        ];
    }

    public function openRoom()
    {
        $agent_logic = new AgentLogic();

        $start_time = Carbon::now()->subMonth()->toDateTimeString();
        $end_time = Carbon::now()->toDateTimeString();
        $this->params['page_size'] = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        return [
            'recharge_list' => $agent_logic->openRoomRecord($this->params, $start_time, $end_time)
        ];
    }

    public function bindPlayer()
    {
        $this->params['page_size'] = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $login_user = Auth::user();

        if ($login_user->hasRole(Constants::$admin_role)) {
            if (isset($this->params['query_str']) && !empty($this->params['query_str'])) {
                $bind_player_record = UserBindPlayer::where([
                    'user_name' => $this->params['query_str']
                ])->simplePaginate($this->params['page_size']);
            } else {
                $bind_player_record = UserBindPlayer::simplePaginate($this->params['page_size']);
            }
        } else {
            $bind_player_record = UserBindPlayer::where([
                'user_name' => $login_user->user_name
            ])->simplePaginate($this->params['page_size']);
        }

        return [
            'recharge_list' => $bind_player_record
        ];
    }

    public function replaceRecharge()
    {
        $this->params['page_size'] = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        return [
            'recharge_flows' => (new UserBindLogic())->replaceRechargeFlows($this->params['page_size'])
        ];
    }
}