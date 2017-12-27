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
use App\Models\CashOrder;
use App\Models\GeneralAgents;
use App\Models\InviteCode;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class FirstAgentLogic extends BaseLogic
{
    /**
     * @param     $params
     * @param     $page_size
     * @param int $status
     * @return mixed
     */
    public function getFirstAgentList($params, $page_size, $status = Constants::COMMON_ENABLE)
    {
        $where = [
            'role_id' => Constants::ROLE_TYPE_FIRST_AGENT,
            'status'  => $status,
        ];
        if (isset($params['query_str']) && !empty($params['query_str'])) {
            // 分析query_str类型
            if (is_numeric($params['query_str']) &&
                (strlen($params['query_str']) == Constants::FIRST_AGENT_INVITE_CODE_LENGTH)
            ) {
                // 邀请码查询
                $where['code'] = $params['query_str'];
                $users = User::where($where)
                    ->orderBy('id', 'desc')
                    ->simplePaginate($page_size);
            } else {
                // 姓名查询
                $users = User::where($where)
                    ->where('user_name', 'like', "%{$params['query_str']}%")
                    ->orderBy('id', 'desc')->simplePaginate($page_size);
            }
        } else {
            $users = User::where($where)
                ->orderBy('id', 'desc')
                ->simplePaginate($page_size);
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

        if ($invite_code['is_used'] == Constants::COMMON_DISABLE) {
            throw new BaheException(BaheException::INVITE_CODE_NOT_USED_CODE);
        }

        return $invite_code;
    }

    /**
     * @param $invite_code
     * @param $start_time
     * @param $end_time
     * @param $page_size
     * @return \Illuminate\Contracts\Pagination\Paginator|Paginator
     */
    public function getAgentRechargeList($invite_code, $start_time, $end_time, $page_size)
    {
        $users = User::where([
            'role_id' => Constants::ROLE_TYPE_AGENT,
            'invite_code' => $invite_code,
        ])->get()->toArray();

        if (empty($users) || ($start_time > $end_time)) {
            $recharge_flows = new Paginator([], $page_size);
        } else {
            $recharge_flows = TransactionFlow::whereIn('recipient_id', array_column($users, 'id'))
                ->whereIn('recipient_type', Constants::$agent_role_type)
                ->whereBetween('created_at', [
                    $start_time,
                    $end_time,
                ])
                ->orderBy('id', 'desc')
                ->simplePaginate($page_size);
        }

        return $recharge_flows;
    }

    public function getWeekCashOrder($agent_level = Constants::AGENT_LEVEL_FIRST, $page_size)
    {
        $cash_orders = CashOrder::where([
                'type' => $agent_level,
            ])
            ->where('amount', '>', 0)
            ->orderBy('id', 'desc')
            ->simplePaginate($page_size);

        return $cash_orders;
    }

    public function getLevelAgentSaleAmount($user_id, $start_time = null, $end_time = null, $page_size = null)
    {
        $first_agent = User::where([
            'id' => $user_id
        ])->first();

        if (empty($first_agent)) {
            throw new BaheException(BaheException::USER_NOT_EXIST_CODE);
        }

        if (!$first_agent->hasRole(Constants::$level_agent)) {
            throw new BaheException(BaheException::AGENT_NOT_VALID_CODE);
        }

        // 获取所有的代理充值额度
        $agents = User::where([
            'invite_code' => $first_agent->code,
            'role_id' => Constants::ROLE_TYPE_AGENT,
        ])->get()->toArray();
        $agent_ids = array_column($agents, 'id');

        $group_by = 'recipient_id';
        $select = 'recipient_id as user_id, sum(num) as sum';
        $where = [
            'recharge_type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
            'status' => Constants::COMMON_ENABLE,
        ];
        if (!empty($start_time) && !empty($end_time)) {
            if (empty($page_size)) {
                $flows = TransactionFlow::whereIn('recipient_id', $agent_ids)
                    ->whereIn('recipient_type', Constants::$agent_role_type)
                    ->where($where)
                    ->whereBetween('created_at', [$start_time, $end_time])
                    ->groupBy($group_by)
                    ->selectRaw($select)
                    ->get();
            } else {
                $flows = TransactionFlow::whereIn('recipient_id', $agent_ids)
                    ->whereIn('recipient_type', Constants::$agent_role_type)
                    ->where($where)
                    ->whereBetween('created_at', [$start_time, $end_time])
                    ->groupBy($group_by)
                    ->selectRaw($select)
                    ->simplePaginate($page_size);
            }

        } else {
            if (empty($page_size)) {
                $flows = TransactionFlow::whereIn('recipient_id', $agent_ids)
                    ->whereIn('recipient_type', Constants::$agent_role_type)
                    ->where($where)
                    ->groupBy($group_by)
                    ->selectRaw($select)
                    ->get();
            } else {
                $flows = TransactionFlow::whereIn('recipient_id', $agent_ids)
                    ->whereIn('recipient_type', Constants::$agent_role_type)
                    ->where($where)
                    ->groupBy($group_by)
                    ->selectRaw($select)
                    ->simplePaginate($page_size);
            }
        }


        return $flows;
    }

    public function getCurrentAgentIncomeStat($agent_id)
    {
        $income_stat = [];
        $start_of_week = Carbon::now()->startOfWeek()->toDateTimeString();

        $agent_amount = $this->getLevelAgentSaleAmount($agent_id, $start_of_week, Carbon::now()->toDateTimeString());
        $agent_sale_sum = array_sum(array_column($agent_amount->toArray(), 'sum')) * Constants::ROOM_CARD_PRICE;

        $income_stat['first_agent_sale_amount'] = $agent_sale_sum;
        $income_stat['first_agent_sale_commission'] = $agent_sale_sum * Constants::COMMISSION_TYPE_FIRST_TO_AGENT_RATE;
        $income_stat['agent_sale_amount'] = $this->getAgentSaleAmount($agent_id) * Constants::ROOM_CARD_PRICE;
        $income_stat['last_week_income'] = $this->getLevelAgentLastWeekIncome($agent_id);
        $income_stat['current_week_income'] = $income_stat['first_agent_sale_commission'];

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
        $last_week_day = Carbon::now()->subWeek();
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
                'type' => Constants::AGENT_LEVEL_FIRST
            ])
            ->selectRaw('week, amount, status')
            ->orderBy('id', 'desc')
            ->simplePaginate($page_size);

        return $cash_orders;
    }
}