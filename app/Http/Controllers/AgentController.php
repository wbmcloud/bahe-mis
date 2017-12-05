<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Logic\AccountLogic;
use App\Logic\AgentLogic;
use App\Logic\UserLogic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{

    public function agentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $agent_logic = new AgentLogic();
        $user_logic  = new UserLogic();

        $users  = $agent_logic->getAgentList($this->params, $page_size);
        $cities = $user_logic->getOpenCities();

        return [
            'cities' => $cities,
            'agents' => $users,
        ];
    }

    public function banAgentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $agent_logic = new AgentLogic();
        $users       = isset($this->params['query_str']) && !empty($this->params['query_str']) ?
            $agent_logic->getAgentList($this->params['query_str'], $page_size, Constants::COMMON_DISABLE) :
            $agent_logic->getAgentList(null, $page_size, Constants::COMMON_DISABLE);

        return [
            'agents' => $users,
        ];
    }

    public function agentInfo()
    {
        $agent_logic = new AgentLogic();
        $user        = $agent_logic->getAgentInfoById($this->params['id']);

        return [
            'agent_info' => $user
        ];
    }

    public function rechargeList()
    {
        $page_size  = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        $start_time = isset($this->params['start_date']) ? $this->params['start_date'] :
            Carbon::today()->toDateString();
        $end_time   = isset($this->params['end_date']) ? $this->params['end_date'] :
            Carbon::tomorrow()->toDateString();

        $agent_logic   = new AgentLogic();
        if (Auth::user()->hasRole(Constants::$admin_role)) {
            $recharge_list = $agent_logic->getAgentRechargeFlows($this->params['id'], $start_time,
                $end_time, $page_size);
        } else {
            $recharge_list = $agent_logic->getAgentConsumeFlows(Auth::id(), $start_time,
                $end_time, $page_size);
        }

        return [
            'recharge_list' => $recharge_list
        ];
    }

    public function showOpenRoomForm()
    {
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

        $account_logic = new AccountLogic();
        $account      = $account_logic->getAgentBalance();

        return [
            'agent' => $user,
            'account' => $account
        ];
    }

    public function openRoom()
    {
        $user          = Auth::user();
        $agent_logic   = new AgentLogic();

        $open_room_params['server_id'] = $this->params['server_id'];
        //$open_room_params['model'] = $this->params['model'];
        $open_room_params['extend_type'] = $this->params['extend_type'];
        $open_room_params['open_rands'] = $this->params['open_rands'];
        $open_room_params['top_mutiple'] = $this->params['top_mutiple'];
        isset($this->params['voice_open']) && ($open_room_params['voice_open'] = $this->params['voice_open']);

        $open_room_res = $agent_logic->openRoom($user, $open_room_params);

        return [
            'prompt' => Constants::SUCCESS_PROMPT_OPEN_ROOM,
            'data' => [
                'room_id' => $open_room_res['room_id'],
                'req_params' => $agent_logic->renderOpenRoomParams($open_room_params)
            ],
        ];
    }

}