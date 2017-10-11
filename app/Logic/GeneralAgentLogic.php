<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Common\Constants;
use App\Common\Utils;
use App\Exceptions\BaheException;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Models\CashOrder;
use App\Models\GeneralAgents;
use App\Models\InviteCode;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralAgentLogic extends BaseLogic
{
    /**
     * @param     $params
     * @param     $page_size
     * @param int $status
     * @return mixed
     */
    public function getGeneralAgentList($params, $page_size, $status = Constants::COMMON_ENABLE)
    {
        $where = [
            'role_id' => Constants::ROLE_TYPE_GENERAL_AGENT,
            'status'  => $status,
        ];
        if (isset($params['query_str']) && !empty($params['query_str'])) {
            // 分析query_str类型
            if (is_numeric($params['query_str']) &&
                (strlen($params['query_str']) == Constants::INVITE_CODE_LENGTH)
            ) {
                // 邀请码查询
                $where['code'] = $params['query_str'];
                $users = User::where($where)->orderBy('id', 'desc')->paginate($page_size);
            } else {
                // 姓名查询
                $users = User::where($where)
                    ->where('name', 'like', "%{$params['query_str']}%")
                    ->orderBy('id', 'desc')->paginate($page_size);
            }
        } else {
            $users = User::where($where)
                ->orderBy('id', 'desc')
                ->paginate($page_size);
        }

        return $users;
    }

    /**
     * @param $invite_codes
     * @return mixed
     */
    public function getAgentCount($invite_codes)
    {
        $where = [
            'role_id'     => Constants::ROLE_TYPE_AGENT,
            'status'      => Constants::COMMON_ENABLE,
        ];
        return User::where($where)
            ->whereIn('invite_code', $invite_codes)
            ->groupBy('invite_code')
            ->selectRaw('invite_code, count(id) as count')
            ->get();
    }

    /**
     * @param $invite_codes
     * @return mixed
     */
    public function getFirstAgentCount($invite_codes)
    {
        $where = [
            'role_id'     => Constants::ROLE_TYPE_FIRST_AGENT,
            'status'      => Constants::COMMON_ENABLE,
        ];
        return User::where($where)
            ->whereIn('invite_code', $invite_codes)
            ->groupBy('invite_code')
            ->selectRaw('invite_code, count(id) as count')
            ->get();
    }

    /**
     * @param $invite_code
     * @return mixed
     * @throws BaheException
     */
    public function getInviteCode($invite_code)
    {
        $invite_code = InviteCode::where([
            'invite_code' => $invite_code,
            'type' => Constants::INVITE_CODE_TYPE_GENERAL_AGENT
        ])->first();
        if (empty($invite_code)) {
            throw new BaheException(BaheException::INVITE_CODE_NOT_VALID_CODE);
        }

        if ($invite_code['is_used'] == Constants::COMMON_ENABLE) {
            throw new BaheException(BaheException::INVITE_CODE_USED_CODE);
        }

        return $invite_code;
    }

    /**
     * @param $invite_code
     * @param $start_time
     * @param $end_time
     * @param $page_size
     * @return LengthAwarePaginator
     */
    public function getAgentRechargeList($invite_code, $start_time, $end_time, $page_size)
    {
        $users = User::where([
            'role_id' => Constants::ROLE_TYPE_AGENT,
            'invite_code' => $invite_code,
        ])->get()->toArray();

        if (empty($users) || ($start_time > $end_time)) {
            $recharge_flows = new LengthAwarePaginator([], 0, $page_size);
        } else {
            $recharge_flows = TransactionFlow::whereIn('recipient_id', array_column($users, 'id'))
                ->whereBetween('created_at', [
                    $start_time,
                    $end_time,
                ])
                ->orderBy('id', 'desc')
                ->paginate($page_size);
        }

        return $recharge_flows;
    }

    /**
     * @param $params
     * @return LengthAwarePaginator
     */
    public function getFirstAgentIncomeList($params)
    {
        $income_first_agents = [];

        $where = [
            'role_id' => Constants::ROLE_TYPE_FIRST_AGENT,
            'invite_code' => $params['invite_code'],
        ];
        if (isset($params['query_str']) && !empty($params['query_str'])) {
            $where['name'] = $params['query_str'];
        }

        $first_agents = User::where($where)->get()->toArray();

        foreach ($first_agents as $first_agent) {
            $agents = User::where([
                'role_id' => Constants::ROLE_TYPE_AGENT,
                'invite_code' => $first_agent['code'],
            ])->get()->toArray();
            if (empty($agents)) {
                continue;
            }
            if (isset($params['start_time']) && !empty($params['start_time']) &&
                isset($params['end_time']) && !empty($params['end_time'])) {
                $total_income = TransactionFlow::whereIn('recipient_id', array_column($agents, 'id'))
                    ->whereBetween('created_at', [$params['start_time'], $params['end_time']])
                    ->sum('num');
            } else {
                $total_income = TransactionFlow::whereIn('recipient_id', array_column($agents, 'id'))
                    ->sum('num');
            }

            if (empty($total_income)) {
                continue;
            }

            $first_agent['sum'] = $total_income;
            $income_first_agents[] = $first_agent;
        }

        if (isset($params['page_size']) && !empty($params['page_size'])) {
            return new LengthAwarePaginator($income_first_agents, count($income_first_agents), $params['page_size']);;
        }

        return $income_first_agents;
    }

    public function getLastWeekCashOrder($agent_level = Constants::AGENT_LEVEL_FIRST, $page_size)
    {
        $last_week_day = Carbon::now()->subWeek();
        $last_week = $last_week_day->weekOfYear;

        $cash_orders = CashOrder::where([
                'week' => $last_week,
                'type' => $agent_level,
            ])
            ->where('amount', '>', 0)
            ->paginate($page_size);

        return $cash_orders;
    }

    public function getLevelAgentSaleAmount($user_id, $start_time = null, $end_time = null, $page_size = null)
    {
        $general_agent = User::where([
            'id' => $user_id
        ])->first();

        if (empty($general_agent)) {
            throw new BaheException(BaheException::USER_NOT_EXIST_CODE);
        }

        if (!$general_agent->hasRole(Constants::$level_agent)) {
            throw new BaheException(BaheException::AGENT_NOT_VALID_CODE);
        }

        // 获取所有的代理充值额度
        $agents = User::where([
            'invite_code' => $general_agent->code,
            'role_id' => Constants::ROLE_TYPE_AGENT,
        ])->get()->toArray();
        $agent_ids = array_column($agents, 'id');

        $group_by = 'recipient_id';
        $select = 'recipient_id as id, sum(num) as sum';
        $where = [
            'recharge_type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
            'status' => Constants::COMMON_ENABLE,
        ];
        if (!empty($start_time) && !empty($end_time)) {
            if (empty($page_size)) {
                $flows = TransactionFlow::whereIn('recipient_id', $agent_ids)
                    ->where($where)
                    ->whereBetween('created_at', [$start_time, $end_time])
                    ->groupBy($group_by)
                    ->selectRaw($select)
                    ->get();
            } else {
                $flows = TransactionFlow::whereIn('recipient_id', $agent_ids)
                    ->where($where)
                    ->whereBetween('created_at', [$start_time, $end_time])
                    ->groupBy($group_by)
                    ->selectRaw($select)
                    ->paginate();
            }

        } else {
            if (empty($page_size)) {
                $flows = TransactionFlow::whereIn('recipient_id', $agent_ids)
                    ->where($where)
                    ->groupBy($group_by)
                    ->selectRaw($select)
                    ->get();
            } else {
                $flows = TransactionFlow::whereIn('recipient_id', $agent_ids)
                    ->where($where)
                    ->groupBy($group_by)
                    ->selectRaw($select)
                    ->paginate();
            }
        }

        return $flows;
    }

    public function getCurrentAgentIncomeStat($user)
    {
        $income_stat = [];
        $start_of_week = Carbon::now()->startOfWeek()->toDateTimeString();
        $end_time = Carbon::now()->toDateTimeString();

        $agent_amount = $this->getLevelAgentSaleAmount($user->id, $start_of_week, $end_time);
        $agent_sale_sum = array_sum(array_column($agent_amount->toArray(), 'sum')) * Constants::ROOM_CARD_PRICE;

        $first_agent_amount = $this->getFirstAgentIncomeList([
            'invite_code' => $user->code,
            'start_time' => $start_of_week,
            'end_time' => $end_time,
        ]);
        $first_agent_sum = array_sum(array_column($first_agent_amount, 'total_income')) * Constants::ROOM_CARD_PRICE;

        $income_stat['first_agent_sale_amount'] = $agent_sale_sum;
        $income_stat['first_agent_sale_commission'] = $agent_sale_sum * Constants::COMMISSION_TYPE_GENERAL_TO_AGENT_RATE;
        $income_stat['general_agent_sale_amount'] = $first_agent_sum;
        $income_stat['general_agent_sale_commission'] = $first_agent_sum * Constants::COMMISSION_TYPE_GENERAL_TO_FIRST_RATE;
        $income_stat['agent_sale_amount'] = $this->getAgentSaleAmount($user->id) * Constants::ROOM_CARD_PRICE;
        $income_stat['current_week_income'] = $income_stat['first_agent_sale_commission'] + $income_stat['general_agent_sale_commission'];
        $income_stat['last_week_income'] = $this->getLevelAgentLastWeekIncome($user->id);

        return $income_stat;
    }

    public function getAgentSaleAmount($agent_id, $start_time = null, $end_time = null)
    {
        return TransactionFlow::where([
                'initiator_id' => $agent_id,
                'status' => Constants::COMMON_ENABLE,
            ])
            ->whereIn('recharge_type', [
                COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                Constants::COMMAND_TYPE_OPEN_ROOM,
            ])
            ->whereBetween('created_at', [$start_time, $end_time])
            ->sum('num');
    }


    public function getLevelAgentLastWeekIncome($agent_id)
    {
        $last_week_day = Carbon::now()->previousWeekday();
        $last_week = $last_week_day->weekOfYear;

        return CashOrder::where([
            'relation_id' => $agent_id,
            'week' => $last_week
        ])->sum('amount');
    }

    public function getLevelAgentSaleAmountDetail($agent_id, $page_size)
    {
        $start_of_week = Carbon::now()->startOfWeek()->toDateTimeString();

        return $this->getLevelAgentSaleAmount($agent_id,
            $start_of_week, Carbon::now()->toDateTimeString(), $page_size);
    }

    public function getAgentInfoByIds($agent_ids)
    {
        $agents = User::whereIn('id', $agent_ids)->get()->toArray();
        return array_column($agents, null, 'id');
    }

    public function getLevelAgentCashOrderList($agent_id, $page_size)
    {
        $cash_orders = CashOrder::where([
                'relation_id' => $agent_id,
                'type' => Constants::AGENT_LEVEL_GENERAL
            ])
            ->selectRaw('week, amount, status')
            ->paginate($page_size);

        return $cash_orders;
    }
}