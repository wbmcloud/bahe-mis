<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Common\Constants;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Models\Accounts;
use App\Models\DayAgentStat;
use App\Models\DayFlowStat;
use App\Models\DayRounds;
use App\Models\GamePlayer;
use App\Models\GeneralAgents;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;

class StatLogic extends BaseLogic
{
    /**
     * @param $size
     * @return mixed
     */
    public function getStatAgentList($size)
    {
        $day_agents = DayAgentStat::orderBy('id', 'desc')->take($size)->get()->toArray();
        array_multisort(array_column($day_agents, 'id'), SORT_ASC, $day_agents);
        return [
            'list' => $day_agents
        ];
    }

    /**
     * @param $size
     * @return array
     */
    public function getStatFlowList($size)
    {
        $flows = DayFlowStat::orderBy('id', 'desc')->take($size)->get()->toArray();
        array_multisort(array_column($flows, 'id'), SORT_ASC, $flows);
        return [
            'list' => $flows
        ];
    }

    /**
     * @param $size
     * @return array
     */
    public function getStatRoundsList($size)
    {
        $rounds = DayRounds::orderBy('id', 'desc')->take($size)->get()->toArray();
        array_multisort(array_column($rounds, 'id'), SORT_ASC, $rounds);
        return [
            'list' => $rounds
        ];
    }


    /**
     * 获取剩余房卡数
     * @return mixed
     */
    public function getTotalBalanceCard()
    {
        return Accounts::sum('card_balance');
    }

    /**
     * 获取总房卡数
     * @return mixed
     */
    public function getTotalCard()
    {
        return Accounts::sum('card_total');
    }

    /**
     * 获取今天房卡消耗数
     * @return mixed
     */
    public function getTodayConsumeCard()
    {
        // 代开房+用户充值数量
        return TransactionFlow::where([
            'recipient_type' => Constants::ROLE_TYPE_USER,
            'recharge_type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD
        ])
            ->where('created_at', '>=', Carbon::today()->toDateTimeString())
            ->sum('num');
    }

    /**
     * 获取今天房卡充值数
     * @return mixed
     */
    public function getTodayRechargeCard()
    {
        return TransactionFlow::whereIn('recipient_type', Constants::$agent_role_type)
            ->where('recharge_type', COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD)
            ->where('created_at', '>=', Carbon::today()->toDateTimeString())
            ->sum('num');
    }

    /**
     * 获取新增代理数
     * @return int
     */
    public function getTodayNewAgents()
    {
        return User::whereIn('role_id', Constants::$agent_role_type)
            ->where('created_at', '>=', Carbon::today()->toDateTimeString())
            ->count();
    }

    /**
     * 获取总的代理人数
     * @return int
     */
    public function getTotalAgents()
    {
        return User::whereIn('role_id', Constants::$agent_role_type)
            ->count();
    }

    /**
     * 获取总得游戏玩家数
     * @return int
     */
    public function getTotalGamePlayer()
    {
        return GamePlayer::count();
    }
}