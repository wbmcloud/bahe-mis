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
use Illuminate\Pagination\Paginator;
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
            $users     = User::where([
                'invite_code' => $login_user->code,
                'city_id'     => $login_user->city_id,
                'role_id'     => Constants::ROLE_TYPE_AGENT,
                'status'      => Constants::COMMON_ENABLE
            ])->get()->toArray();
            $agent_ids = array_column($users, 'uk');

            return PlayerBindAgent::whereIn('agent_id', $agent_ids)
                ->orderBy('id', 'desc')
                ->simplePaginate($page_size);
        }

        if ($login_user->role_id == Constants::ROLE_TYPE_GENERAL_AGENT) {
            $ids             = [];
            $first_agents    = User::where([
                'invite_code' => $login_user->code,
                'city_id'     => $login_user->city_id,
                'role_id'     => Constants::ROLE_TYPE_FIRST_AGENT,
                'status'      => Constants::COMMON_ENABLE
            ])->get();
            $first_agent_ids = array_column($first_agents->toArray(), 'uk');
            $ids             = array_merge($ids, $first_agent_ids);

            foreach ($first_agents as $first_agent) {
                $agents = User::where([
                    'invite_code' => $first_agent->code,
                    'city_id'     => $first_agent->city_id,
                    'role_id'     => Constants::ROLE_TYPE_AGENT,
                    'status'      => Constants::COMMON_ENABLE
                ])->get()->toArray();

                $agent_ids = array_column($agents, 'uk');
                $ids       = array_merge($ids, $agent_ids);
            }

            return PlayerBindAgent::whereIn('agent_id', $ids)
                ->orderBy('id', 'desc')
                ->simplePaginate($page_size);
        }
    }

    public function getReplaceRechargeRecord($agent_id, $start_time, $end_time, $page_size)
    {
        return TransactionFlow::where([
                'initiator_id'   => $agent_id,
                'recipient_type' => Constants::ROLE_TYPE_USER,
                'recharge_type'  => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                'is_replace'     => Constants::RECHARGE_REPLACE_FLAG
            ])
            ->whereBetween('created_at', [$start_time, $end_time])
            ->orderBy('id', 'desc')
            ->simplePaginate($page_size);
    }

    public function replaceRechargeFlows($page_size)
    {
        $user = Auth::user();
        $recharge_agent_ids = [];

        switch ($user->role_id) {
            case Constants::ROLE_TYPE_AGENT:
            case Constants::ROLE_TYPE_FIRST_AGENT:
                //查询是否有总代理或者总监代充
                do {
                    $invite_code = $user->invite_code;
                    if (empty($invite_code)) {
                        break;
                    }
                    $user = User::where([
                        'city_id' => $user->city_id,
                        'code' => $invite_code,
                        'status' => Constants::COMMON_ENABLE
                    ])->first();
                    if (!empty($user)) {
                        $recharge_agent_ids[] = $user->id;
                    }
                } while (!empty($user));
                break;
        }

        if (empty($recharge_agent_ids)) {
            return new Paginator([], $page_size);
        }

        return TransactionFlow::whereIn('initiator_id', $recharge_agent_ids)
            ->where([
                'recipient_type' => Constants::ROLE_TYPE_USER,
                'recharge_type'  => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                'is_replace'     => Constants::RECHARGE_REPLACE_FLAG
            ])
            ->orderBy('id', 'desc')
            ->simplePaginate($page_size);
    }
}
