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
use App\Library\Protobuf\COMMAND_TYPE;
use App\Library\Protobuf\Protobuf;
use App\Library\TcpClient;
use App\Models\TransactionFlow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RechargeLogic extends BaseLogic
{
    protected function checkAgentRechargeRole($user_name)
    {
        $user = User::where('name', $user_name)->first();
        if (empty($user)) {
            throw new SlException(SlException::USER_NOT_EXIST_CODE);
        }

        $user_logic = new UserLogic();
        $role       = $user_logic->getRoleByUser($user);

        if ($role['name'] !== Constants::ROLE_AGENT) {
            throw new SlException(SlException::RECHARGE_ROLE_NOT_AGENT_CODE);
        }

        return $role;
    }

    protected function checkAgentRechargeAuth()
    {
        $user = Auth::user();
        if (empty($user)) {
            return redirect()->intended('login');
        }

        $user_logic = new UserLogic();
        $role       = $user_logic->getRoleByUser($user);

        if ($role['name'] === Constants::ROLE_TYPE_AGENT) {
            throw new SlException(SlException::AGENT_NOT_RECHARGE_FOR_AGENT_CODE);
        }

        return $role;
    }

    public function agentRecharge($params)
    {
        $this->checkAgentRechargeAuth();
        $recharge_user_role = $this->checkAgentRechargeRole($params['user_name']);
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $this->saveAgentTransactionFlow($user, $recharge_user_role, $params);

            $account_logic = new AccountLogic();
            $account       = $account_logic->getAccountByUserNameAndType($params['user_name'],
                $params['recharge_type']);

            if (empty($account)) {
                $params['user_id'] = $recharge_user_role['pivot']['user_id'];
                $account_logic->createAccount($params);
            } else {
                $account->balance += $params['num'];
                $account->total   += $params['num'];
                $account->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new SlException(SlException::FAIL_CODE);
        }

        return [];
    }

    /**
     * @param $user
     * @param $recharge_role
     * @param $params
     * @return TransactionFlow
     */
    public function saveAgentTransactionFlow($user, $recharge_role, $params)
    {
        $transaction_flow                 = new TransactionFlow();
        $transaction_flow->initiator_id   = $user->id;
        $transaction_flow->initiator_name = $user->name;
        $transaction_flow->initiator_type = Constants::$recharge_role_type[$user->roles()->first()->toArray()['name']];
        $transaction_flow->recipient_name = $params['user_name'];
        $transaction_flow->recipient_id   = $recharge_role['pivot']['user_id'];
        $transaction_flow->recipient_type = Constants::$recharge_role_type[$recharge_role['name']];
        $transaction_flow->recharge_type  = $params['recharge_type'];
        $transaction_flow->num            = $params['num'];
        $transaction_flow->status         = Constants::COMMON_ENABLE;
        $transaction_flow->save();

        return $transaction_flow;
    }

    /**
     * @param $params
     * @param $command_res
     * @return array
     * @throws SlException
     */
    public function sendGmtUserRecharge($params, &$command_res)
    {
        // 调用idip进行充值
        $inner_meta_register_srv = Protobuf::packRegisterInnerMeta();
        $register_res            = TcpClient::callTcpService($inner_meta_register_srv, true);
        if ($register_res !== $inner_meta_register_srv) {
            throw new SlException(SlException::GMT_SERVER_REGISTER_FAIL_CODE);
        }
        // 调用idip进行充值
        if ($params['recharge_type'] == COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD) {
            $command['item_id'] = Constants::ROOM_CARD_ITEM_ID;
        }
        $command['command_type'] = $params['recharge_type'];
        $command['player_id']    = $params['role_id'];
        $command['count']        = $params['num'];
        $inner_meta_command      = Protobuf::packCommandInnerMeta($command);
        $command_res             = Protobuf::unpackForResponse(TcpClient::callTcpService($inner_meta_command));
        if ($command_res['error_code'] != 0) {
            throw new SlException(SlException::GMT_SERVER_RECHARGE_FAIL_CODE);
        }

        return $command_res;
    }

    public function saveUserTransactionFlow($user, $params, $is_recharged, $recharge_fail_reason)
    {
        $transaction_flow                 = new TransactionFlow();
        $transaction_flow->initiator_id   = $user->id;
        $transaction_flow->initiator_name = $user->name;
        $transaction_flow->initiator_type = Constants::$recharge_role_type[$user->roles()->first()->toArray()['name']];
        $transaction_flow->recipient_id   = $params['role_id'];
        $transaction_flow->recipient_type = Constants::ROLE_TYPE_USER;
        $transaction_flow->recharge_type  = $params['recharge_type'];
        $transaction_flow->num            = $params['num'];

        if ($is_recharged) {
            $transaction_flow->status = Constants::COMMON_ENABLE;
        } else {
            $transaction_flow->recharge_fail_reason = $recharge_fail_reason;
        }
        $transaction_flow->save();

        return $transaction_flow;
    }

    /**
     * @param $params
     * @return array
     * @throws SlException
     */
    public function userRecharge($params)
    {
        $is_recharged = true;
        $user         = Auth::user();

        DB::beginTransaction();
        try {
            if ($user->hasRole(Constants::ROLE_AGENT)) {
                $this->rechargeReduceBalance($user->name, $params['recharge_type'], $params['num']);
            }
            $this->sendGmtUserRecharge($params, $command_res);
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
            if ($e->getCode() == SlException::GMT_SERVER_RECHARGE_FAIL_CODE) {
                $recharge_fail_reason = json_encode($command_res);
            } else {
                $recharge_fail_reason = json_encode([
                    'error_code' => $e->getCode(),
                    'error_msg'  => $e->getMessage(),
                ]);
            }
        }

        $recharge_fail_reason = isset($recharge_fail_reason) ? $recharge_fail_reason : null;
        $this->saveUserTransactionFlow($user, $params, $is_recharged, $recharge_fail_reason);

        if (!$is_recharged) {
            throw new SlException($error_code, $error_message);
        }

        return [];
    }

    /**
     * @param $user_name
     * @param $type
     * @param $num
     * @return mixed
     */
    public function rechargeReduceBalance($user_name, $type, $num)
    {
        $account_logic = new AccountLogic();
        return $account_logic->reduceBalance($user_name, $type, $num);
    }
}