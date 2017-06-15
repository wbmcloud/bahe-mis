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
use App\Models\GeneralAgents;
use App\Models\InviteCode;
use App\Models\TransactionFlow;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
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
        if (isset($params['query_str']) && !empty($params['query_str'])) {
            // 分析query_str类型
            if (is_numeric($params['query_str']) &&
                (strlen($params['query_str']) == Constants::INVITE_CODE_LENGTH)
            ) {
                // 邀请码查询
                $users = GeneralAgents::where([
                    'status'      => $status,
                    'invite_code' => $params['query_str']
                ])->paginate($page_size);
            } else {
                // 姓名查询
                $users = GeneralAgents::where([
                    'status' => $status,
                    'name'   => $params['query_str']
                ])->paginate($page_size);
            }
        } else {
            $users = GeneralAgents::where('status', $status)
                ->paginate($page_size);
        }

        return $users;
    }

    /**
     * @param $params
     * @return GeneralAgents
     */
    public function saveGeneralAgent($params)
    {
        $general_agent              = new GeneralAgents();
        $general_agent->name        = $params['name'];
        $general_agent->invite_code = $params['invite_code'];
        !empty($params['tel']) && ($general_agent->tel = $params['tel']);
        !empty($params['bank_card']) && ($general_agent->bank_card = $params['bank_card']);
        !empty($params['id_card']) && ($general_agent->id_card = $params['id_card']);
        $general_agent->save();

        return $general_agent;
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

        return $invite_code;
    }

    /**
     * @param $params
     * @return array
     * @throws SlException
     */
    public function addGeneralAgent($params)
    {
        // 校验邀请码合法性
        $invite_code = $this->getInviteCode($params['invite_code']);

        DB::beginTransaction();
        try {
            $this->saveGeneralAgent($params);
            $invite_code->is_used = Constants::COMMON_ENABLE;
            $invite_code->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new SlException(SlException::FAIL_CODE);
        }

        return [];
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
                ->paginate($page_size);
        }

        return $recharge_flows;
    }
}