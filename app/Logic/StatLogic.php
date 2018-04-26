<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Common\Constants;
use App\Exceptions\BaheException;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Models\Accounts;
use App\Models\DayAgentFlowStat;
use App\Models\DayAgentStat;
use App\Models\DayFlowStat;
use App\Models\DayGamePlayerLoginLog;
use App\Models\DayRounds;
use App\Models\GamePlayer;
use App\Models\GeneralAgents;
use App\Models\LoginLog;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
     * @param $city_id
     * @param $game_type
     * @param $size
     * @return array
     * @throws BaheException
     */
    public function getStatFlowList($city_id, $game_type, $size)
    {
        // 获取game_server_id
        $game_server = (new GameLogic())->getGameServerByCityIdAndType($city_id, $game_type);
        if (empty($game_server)) {
            throw new BaheException(BaheException::GAME_SERVER_NOT_FOUND_CODE);
        }

        $flows = DayFlowStat::where('game_server_id', $game_server['id'])
            ->orderBy('id', 'desc')->take($size)->get()->toArray();

        array_multisort(array_column($flows, 'id'), SORT_ASC, $flows);
        return [
            'list' => $flows
        ];
    }

    /**
     * @param $city_id
     * @param $size
     * @return array
     */
    public function getStatAgentFlowList($city_id, $size)
    {
        if ($city_id == Constants::CITY_ID_ALL) {
            $flows = DayAgentFlowStat::orderBy('id', 'desc')->take($size)->get()->toArray();
        } else {
            $flows = DayAgentFlowStat::where('city_id', $city_id)
                ->orderBy('id', 'desc')->take($size)->get()->toArray();
        }
        array_multisort(array_column($flows, 'id'), SORT_ASC, $flows);
        return [
            'list' => $flows
        ];
    }

    /**
     * @param $city_id
     * @param $game_type
     * @param $size
     * @return array
     * @throws BaheException
     */
    public function getStatRoundsList($city_id, $game_type, $size)
    {
        // 获取game_server_id
        $game_server = (new GameLogic())->getGameServerByCityIdAndType($city_id, $game_type);
        if (empty($game_server)) {
            throw new BaheException(BaheException::GAME_SERVER_NOT_FOUND_CODE);
        }

        $rounds = DayRounds::where('game_server_id', $game_server['id'])
            ->orderBy('id', 'desc')->take($size)->get()->toArray();
        array_multisort(array_column($rounds, 'id'), SORT_ASC, $rounds);
        return [
            'list' => $rounds
        ];
    }

    public function getStatDauList($city_id, $game_type, $size)
    {
        // 获取game_server_id
        $game_server = (new GameLogic())->getGameServerByCityIdAndType($city_id, $game_type);
        if (empty($game_server)) {
            throw new BaheException(BaheException::GAME_SERVER_NOT_FOUND_CODE);
        }

        $day_game_player_login_log = DayGamePlayerLoginLog::where('game_server_id', $game_server['id'])
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->take($size)
            ->selectRaw('day, count(1) AS amount')
            ->get()
            ->toArray();
        array_multisort(array_column($day_game_player_login_log, 'day'), SORT_ASC, $day_game_player_login_log);
        return [
            'list' => $day_game_player_login_log,
        ];
    }

    public function getStatWauList($city_id, $game_type, $size)
    {
        // 获取game_server_id
        $game_server = (new GameLogic())->getGameServerByCityIdAndType($city_id, $game_type);
        if (empty($game_server)) {
            throw new BaheException(BaheException::GAME_SERVER_NOT_FOUND_CODE);
        }

        $week_game_player_login_log = DayGamePlayerLoginLog::where('game_server_id', $game_server['id'])
            ->groupBy('week')
            ->orderBy('week', 'desc')
            ->take($size)
            ->selectRaw('week, COUNT(DISTINCT player_id) AS amount')
            ->get()
            ->toArray();
        array_multisort(array_column($week_game_player_login_log, 'week'), SORT_ASC, $week_game_player_login_log);
        return [
            'list' => $week_game_player_login_log,
        ];
    }

    public function getStatMauList($city_id, $game_type, $size)
    {
        // 获取game_server_id
        $game_server = (new GameLogic())->getGameServerByCityIdAndType($city_id, $game_type);
        if (empty($game_server)) {
            throw new BaheException(BaheException::GAME_SERVER_NOT_FOUND_CODE);
        }

        $month_game_player_login_log = DayGamePlayerLoginLog::where('game_server_id', $game_server['id'])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take($size)
            ->selectRaw('month, COUNT(DISTINCT player_id) AS amount')
            ->get()
            ->toArray();
        array_multisort(array_column($month_game_player_login_log, 'month'), SORT_ASC, $month_game_player_login_log);
        return [
            'list' => $month_game_player_login_log,
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
     * 获取今天用户充值房卡数
     * @return mixed
     */
    public function getTodayUserRechargeCard()
    {
        return TransactionFlow::where([
                'recipient_type' => Constants::ROLE_TYPE_USER,
                'recharge_type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                'status' => Constants::COMMON_ENABLE
            ])
            ->where('created_at', '>=', Carbon::today()->toDateTimeString())
            ->sum('num');
    }

    /**
     * 获取今天代开房房卡数
     * @return mixed
     */
    public function getTodayOpenRoomCard()
    {
        // 代开房+用户充值数量
        return TransactionFlow::where([
                'recipient_type' => Constants::ROLE_TYPE_USER,
                'recharge_type' => Constants::COMMAND_TYPE_OPEN_ROOM,
                'status' => Constants::COMMON_ENABLE
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
            ->where([
                'recharge_type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                'status' => Constants::COMMON_ENABLE
            ])
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

    /**
     * 获取赠送代理房卡数
     * @return mixed
     */
    public function getGiveTotalCard()
    {
        return TransactionFlow::whereIn('recipient_type', Constants::$agent_role_type)
            ->where('recharge_type', COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD)
            ->sum('give_num');
    }

    /**
     * 获取今天赠送代理房卡数
     * @return mixed
     */
    public function getTodayGiveCard()
    {
        return TransactionFlow::whereIn('recipient_type', Constants::$agent_role_type)
            ->where('recharge_type', COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD)
            ->where('created_at', '>=', Carbon::today()->toDateTimeString())
            ->sum('give_num');
    }

    /**
     * 获取今天活跃代理数
     * @return mixed
     */
    public function getTodayActiveAgents()
    {
        return LoginLog::where('created_at', '>=', Carbon::today()->toDateTimeString())
            ->count(DB::raw('DISTINCT user_id'));
    }
}
