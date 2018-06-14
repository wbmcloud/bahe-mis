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
use App\Models\PlayerBindAgent;
use App\Models\TransactionFlow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserBindLogic extends BaseLogic
{
    public function getMyUserBindList($page_size)
    {
        $login_user = Auth::user();
        if ($login_user->hasRole(Constants::$admin_role)) {
            return PlayerBindAgent::orderBy('id', 'desc')->simplePaginate($page_size);
        }

        return PlayerBindAgent::where([
                'agent_id' => $login_user->uk,
            ])->orderBy('id', 'desc')
            ->simplePaginate($page_size);
    }

    public function getSubUserBindList($page_size)
    {
        $login_user = Auth::user();
        if ($login_user->hasRole(Constants::$admin_role)) {
            //管理员，所有用户的记录
            return PlayerBindAgent::orderBy('id', 'desc')->simplePaginate($page_size);
        }

        if ($login_user->role_id == Constants::ROLE_TYPE_FIRST_AGENT) {
            $users = User::where([
                'invite_code' => $login_user->code,
                'city_id' => $login_user->city_id,
                'status' => Constants::COMMON_ENABLE
            ])->get()->toArray();
            $agent_ids = array_column($users, 'uk');

            return PlayerBindAgent::whereIn('agent_id', $agent_ids)
                ->orderBy('id', 'desc')
                ->simplePaginate($page_size);
        }

        if ($login_user->role_id == Constants::ROLE_TYPE_GENERAL_AGENT) {
            $ids = [];
            $first_agents = User::where([
                'invite_code' => $login_user->code,
                'city_id' => $login_user->city_id,
                'status' => Constants::COMMON_ENABLE
            ])->get();
            $first_agent_ids = array_column($first_agents->toArray(), 'uk');
            $ids = array_merge($ids, $first_agent_ids);

            foreach ($first_agents as $first_agent) {
                $agents = User::where([
                    'invite_code' => $first_agent->code,
                    'city_id' => $first_agent->city_id,
                    'status' => Constants::COMMON_ENABLE
                ])->get()->toArray();

                $agent_ids = array_column($agents, 'uk');
                $ids = array_merge($ids, $agent_ids);
            }
            return PlayerBindAgent::whereIn('agent_id', $ids)
                ->orderBy('id', 'desc')
                ->simplePaginate($page_size);
        }
    }

    public function getReplaceRechargeRecord($agent_id, $start_time, $end_time, $page_size)
    {
        return TransactionFlow::where([
                'initiator_id' => $agent_id,
                'recipient_type' => Constants::ROLE_TYPE_USER,
                'recharge_type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD
            ])
            ->whereBetween('created_at', [$start_time, $end_time])
            ->orderBy('id', 'desc')
            ->simplePaginate($page_size);
    }
}
