<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Common\Constants;
use App\Exceptions\SlException;
use App\Models\CashOrder;
use App\Models\GeneralAgents;
use App\Models\InviteCode;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class FirstAgentLogic extends BaseLogic
{
    /**
     * @param     $params
     * @param     $page_size
     * @param int $status
     * @return mixed
     */
    public function getGeneralAgentList($params, $page_size, $status = Constants::COMMON_ENABLE)
    {
        if (isset($params['query_str']) && !empty($params['query_str'])) {
            // 分析query_str类型
            if (is_numeric($params['query_str']) &&
                (strlen($params['query_str']) == Constants::INVITE_CODE_LENGTH)
            ) {
                // 邀请码查询
                $users = User::where([
                    'role_id' => Constants::ROLE_TYPE_FIRST_AGENT,
                    'status'      => $status,
                    'invite_code' => $params['query_str']
                ])->paginate($page_size);
            } else {
                // 姓名查询
                $users = User::where([
                    'role_id' => Constants::ROLE_TYPE_FIRST_AGENT,
                    'status' => $status,
                    'name'   => $params['query_str']
                ])->paginate($page_size);
            }
        } else {
            $users = User::where([
                'role_id' => Constants::ROLE_TYPE_FIRST_AGENT,
                'status'  => $status,
            ])->paginate($page_size);
        }

        return $users;
    }

    /**
     * @param $invite_code
     * @return mixed
     * @throws SlException
     */
    public function getInviteCode($invite_code)
    {
        $invite_code = InviteCode::where('invite_code', $invite_code)->first();
        if (empty($invite_code)) {
            throw new SlException(SlException::INVITE_CODE_NOT_VALID_CODE);
        }

        if ($invite_code['is_used'] == Constants::COMMON_ENABLE) {
            throw new SlException(SlException::INVITE_CODE_USED_CODE);
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

    public function getLastWeekCashOrder($page_size)
    {
        $last_week_day = Carbon::now()->previousWeekday();
        $last_week = $last_week_day->weekOfYear;

        $cash_orders = CashOrder::where([
            'week' => $last_week
        ])->paginate($page_size);

        return $cash_orders;
    }
}