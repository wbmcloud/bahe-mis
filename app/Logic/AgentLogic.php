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
use App\Library\Protobuf\INNER_TYPE;
use App\Library\Protobuf\Protobuf;
use App\Library\TcpClient;
use App\Models\Accounts;
use App\Models\Role;
use App\Models\TransactionFlow;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AgentLogic extends BaseLogic
{
    /**
     * @param     $query
     * @param     $page_size
     * @param int $status
     * @return mixed
     */
    public function getAgentList($query, $page_size, $status = Constants::COMMON_ENABLE)
    {
        if (is_null($query)) {
            $condition = [
                'role_id' => Constants::ROLE_TYPE_AGENT,
                'status' => $status,
            ];
        } else {
            $condition = [
                'role_id' => Constants::ROLE_TYPE_AGENT,
                'user_name'   => $query,
                'status' => $status,
            ];
        }

        $users = User::where($condition)
            ->paginate($page_size);

        return $users;
    }

    /**
     * @param $id
     * @return mixed
     * @throws BaheException
     */
    public function getAgentInfoById($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            throw new BaheException(BaheException::AGENT_NOT_EXIST_CODE);
        }
        return $user;
    }

    /**
     * @param $agent_id
     * @param $start_time
     * @param $end_time
     * @param $page_size
     * @return mixed
     */
    public function getAgentRechargeFlows($agent_id, $start_time, $end_time, $page_size)
    {
        return TransactionFlow::where([
                'recipient_id' => $agent_id,
            ])
            ->whereBetween('created_at', [$start_time, $end_time])
            ->paginate($page_size);
    }

    /**
     * @param $server_id
     * @param $open_room_res
     * @return array
     * @throws BaheException
     */
    public function sendGmtOpenRoom($server_id, &$open_room_res)
    {
        // 调用idip注册服务器
        $inner_meta_register_srv = Protobuf::packRegisterInnerMeta();
        $register_res            = TcpClient::callTcpService($inner_meta_register_srv, true);
        if (Protobuf::unpackRegister($register_res)->getTypeT() !== INNER_TYPE::INNER_TYPE_REGISTER) {
            throw new BaheException(BaheException::GMT_SERVER_REGISTER_FAIL_CODE);
        }
        // 调用idip代开房
        $open_room['server_id'] = $server_id;
        $inner_meta_open_room   = Protobuf::packOpenRoomInnerMeta($open_room);
        $open_room_res          = Protobuf::unpackOpenRoom(TcpClient::callTcpService($inner_meta_open_room));
        if ($open_room_res['error_code'] != 0) {
            throw new BaheException(BaheException::GMT_SERVER_OPEN_ROOM_FAIL_CODE);
        }

        return $open_room_res;
    }

    /**
     * @param      $user
     * @param bool $is_recharged
     * @param      $recharge_res
     * @param      $recharge_fail_reason
     * @return bool
     */
    public function saveOpenRoomTransactionFlow($user, $is_recharged = false,
                                        $recharge_res, $recharge_fail_reason)
    {
        $transaction_flow                 = new TransactionFlow();
        $transaction_flow->initiator_id   = $user->id;
        $transaction_flow->initiator_name = $user->user_name;
        $transaction_flow->initiator_type = Constants::$recharge_role_type[$user->roles()->first()->toArray()['name']];
        $transaction_flow->recipient_type = Constants::ROLE_TYPE_USER;
        $transaction_flow->recharge_type  = Constants::COMMAND_TYPE_OPEN_ROOM;
        $transaction_flow->num            = Constants::OPEN_ROOM_CARD_REDUCE;

        if ($is_recharged) {
            $transaction_flow->status = Constants::COMMON_ENABLE;
            $transaction_flow->result = json_encode($recharge_res);
        } else {
            $transaction_flow->recharge_fail_reason = $recharge_fail_reason;
        }
        $transaction_flow->save();

        return true;
    }

    /**
     * @param $user
     * @param $server_id
     * @return array
     * @throws BaheException
     */
    public function openRoom($user, $server_id)
    {
        $is_recharged = true;
        DB::beginTransaction();
        try {
            if ($user->hasRole([
                Constants::ROLE_AGENT,
                Constants::ROLE_FIRST_AGENT,
            ])) {
                $account_logic = new AccountLogic();
                $account_logic->reduceBalance($user->user_name,
                    COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                    Constants::OPEN_ROOM_CARD_REDUCE);
            }
            $open_room_res = $this->sendGmtOpenRoom($server_id, $open_room_res);
            DB::commit();
        } catch (\Exception $e) {
            $is_recharged  = false;
            $error_code    = $e->getCode();
            $error_message = $e->getMessage();
            // 关闭socket连接
            if (TcpClient::isAlive()) {
                TcpClient::getSocket()->close();
            }
            DB::rollback();
            if ($e->getCode() == BaheException::GMT_SERVER_OPEN_ROOM_FAIL_CODE) {
                $recharge_fail_reason = json_encode($open_room_res);
            } else {
                $recharge_fail_reason = json_encode([
                    'error_code' => $e->getCode(),
                    'error_msg'  => $e->getMessage(),
                ]);
            }
        }

        $open_room_res        = isset($open_room_res) && !empty($open_room_res) ?
            $open_room_res : null;
        $recharge_fail_reason = isset($recharge_fail_reason) && !empty($recharge_fail_reason) ?
            $recharge_fail_reason : null;

        $this->saveOpenRoomTransactionFlow($user, $is_recharged, $open_room_res,
            $recharge_fail_reason);

        if (!$is_recharged) {
            throw new BaheException($error_code, $error_message);
        }

        return $open_room_res;
    }

    public function getAgentConsumeFlows($agent_id, $start_time, $end_time, $page_size)
    {
        return TransactionFlow::where([
                'initiator_id' => $agent_id
            ])
            ->whereBetween('created_at', [$start_time, $end_time])
            ->paginate();
    }
}