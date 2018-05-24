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
use App\Library\Protobuf\INNER_TYPE;
use App\Library\Protobuf\Protobuf;
use App\Library\TcpClient;
use App\Models\PlayerBindAgent;
use App\Models\TransactionFlow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RechargeLogic extends BaseLogic
{

    public function agentRecharge($params)
    {
        // 判断登录账号是否有代理充值权限
        $login_user = Auth::user();
        $user_logic = new UserLogic();
        $login_role       = $user_logic->getRoleByUser($login_user);
        if (!in_array($login_role['name'], Constants::$admin_role)) {
            throw new BaheException(BaheException::AGENT_NOT_RECHARGE_FOR_AGENT_CODE);
        }

        // 判断充值的代理账号是否合法
        $user = User::where('user_name', $params['user_name'])->first();
        if (empty($user)) {
            throw new BaheException(BaheException::USER_NOT_EXIST_CODE);
        }
        $recharge_user_role       = $user_logic->getRoleByUser($user);
        if (!in_array($recharge_user_role['name'], Constants::$recharge_role)) {
            throw new BaheException(BaheException::AGENT_NOT_VALID_CODE);
        }
        $params['city_id'] = $user->city_id;

        // 给代理充值
        DB::beginTransaction();
        try {
            $this->saveAgentTransactionFlow($login_user, $recharge_user_role, $params);

            $account_logic = new AccountLogic();
            $account_logic->saveAccount($user->account()->first(), $params);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new BaheException(BaheException::FAIL_CODE);
        }

        return Utils::renderSuccess();
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
        $transaction_flow->initiator_name = $user->user_name;
        $transaction_flow->initiator_type = Constants::$recharge_role_type[$user->roles()->first()->toArray()['name']];
        $transaction_flow->recipient_name = $params['user_name'];
        $transaction_flow->recipient_id   = $recharge_role['pivot']['user_id'];
        $transaction_flow->recipient_type = Constants::$recharge_role_type[$recharge_role['name']];
        $transaction_flow->recharge_type  = $params['recharge_type'];
        !empty($params['give_num']) && ($transaction_flow->give_num = $params['give_num']);
        $transaction_flow->num            = $params['num'];
        $transaction_flow->city_id        = $params['city_id'];
        $transaction_flow->status         = Constants::COMMON_ENABLE;
        $transaction_flow->save();

        return $transaction_flow;
    }

    /**
     * @param $params
     * @param $command_res
     * @return array
     * @throws BaheException
     * @throws \Exception
     */
    public function sendGmtUserRecharge($params, &$command_res)
    {
        $server['ip'] = $params['gmt_server_ip'];
        $server['port'] = $params['gmt_server_port'];

        // 调用gmt进行充值
        $inner_meta_register_srv = Protobuf::packRegisterInnerMeta();
        $register_res            = TcpClient::callTcpService($inner_meta_register_srv, true, $server);
        if (Protobuf::unpackRegister($register_res)->getTypeT() !== INNER_TYPE::INNER_TYPE_REGISTER) {
            throw new BaheException(BaheException::GMT_SERVER_REGISTER_FAIL_CODE);
        }

        // 调用gmt进行充值
        if ($params['recharge_type'] == COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD) {
            $command['item_id'] = Constants::ROOM_CARD_ITEM_ID;
        }
        $command['command_type'] = $params['recharge_type'];
        $command['player_id']    = $params['role_id'];
        $command['count']        = $params['num'];
        $inner_meta_command      = Protobuf::packCommandInnerMeta($command);
        $command_res             = Protobuf::unpackForResponse(TcpClient::callTcpService($inner_meta_command, false, $server));
        
        if ($command_res['error_code'] != 0) {
            throw new BaheException(BaheException::GMT_SERVER_RECHARGE_FAIL_CODE);
        }

        return $command_res;
    }

    public function saveUserTransactionFlow($user, $params, $is_recharged, $recharge_fail_reason)
    {
        $transaction_flow                 = new TransactionFlow();
        $transaction_flow->initiator_id   = $user->id;
        $transaction_flow->initiator_name = $user->user_name;
        $transaction_flow->initiator_type = Constants::$recharge_role_type[$user->roles()->first()->toArray()['name']];
        $transaction_flow->recipient_id   = $params['role_id'];
        $transaction_flow->recipient_type = Constants::ROLE_TYPE_USER;
        $transaction_flow->recharge_type  = $params['recharge_type'];
        $transaction_flow->num            = $params['num'];
        $transaction_flow->game_server_id = $params['game_server_id'];
        isset($params['city_id']) && ($transaction_flow->city_id = $params['city_id']);

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
     * @throws BaheException
     */
    public function userRecharge($params)
    {
        $is_recharged = true;
        $user         = Auth::user();

        // 获取game_server_id
        $game_server = (new GameLogic())->getGameServerByCityIdAndType($params['city'], $params['game_type']);
        if (empty($game_server)) {
            throw new BaheException(BaheException::GAME_SERVER_NOT_FOUND_CODE);
        }

        $params['gmt_server_ip'] = $game_server['gmt_server_ip'];
        $params['gmt_server_port'] = $game_server['gmt_server_port'];
        $params['game_server_id'] = $game_server['id'];
        !empty($game_server['city_id']) && ($params['city_id'] = $game_server['city_id']);

        //判断充值的角色id是否已经有绑定，如果绑定，必须是绑定的代理可以进行充值
        if ($user->hasRole(Constants::$recharge_role)) {
            $agent_relation = PlayerBindAgent::where([
                'player_id' => $params['player_id'],
                'type' => Constants::GAME_TYPE_DDZ,
                'status' => Constants::COMMON_ENABLE,
            ])->first();

            if (!empty($agent_relation) && ($agent_relation['agent_id'] != $user->uk)) {
                throw new BaheException(BaheException::BIND_AGENT_NOT_VALID_CODE);
            }
        }

        DB::beginTransaction();
        try {
            if ($user->hasRole(Constants::$recharge_role)) {
                if ($params['num'] < 0) {
                    throw new BaheException(BaheException::PARAMS_INVALID);
                }
                $account_logic = new AccountLogic();
                $account_logic->reduceBalance($user->user_name,
                    $params['recharge_type'], $params['num']);
            }
            $this->sendGmtUserRecharge($params, $command_res);
            DB::commit();
        } catch (\Exception $e) {
            $is_recharged  = false;
            $error_code    = $e->getCode();
            $error_message = $e->getMessage();
            // 关闭socket连接
            if (TcpClient::isAlive()) {
                TcpClient::getSocket([
                    'ip' => $params['gmt_server_ip'],
                    'port' => $params['gmt_server_port']
                ])->close();
            }
            DB::rollback();
            if ($e->getCode() == BaheException::GMT_SERVER_RECHARGE_FAIL_CODE) {
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
            throw new BaheException($error_code, $error_message);
        }

        return Utils::renderSuccess();
    }

    /**
     * @param $params
     * @param $start_time
     * @param $end_time
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function agentRechargeRecord($params, $start_time, $end_time)
    {
        if (isset($params['query_str']) && !empty($params['query_str'])) {
            return TransactionFlow::where([
                    'recipient_name' => $params['query_str'],
                ])
                ->whereIn('recipient_type', Constants::$agent_role_type)
                ->whereIn('recharge_type', Constants::$recharge_type)
                ->orderBy('id', 'desc')
                ->simplePaginate($params['page_size']);
        }

        return TransactionFlow::whereIn('recipient_type', Constants::$agent_role_type)
            ->whereIn('recharge_type', Constants::$recharge_type)
            ->whereBetween('created_at', [$start_time, $end_time])
            ->orderBy('id', 'desc')
            ->simplePaginate($params['page_size']);

    }

    /**
     * @param $params
     * @param $start_time
     * @param $end_time
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function userRechargeRecord($params, $start_time, $end_time)
    {
        if (isset($params['query_str']) && !empty($params['query_str'])) {
            $transaction_flow = TransactionFlow::where([
                    'initiator_name' => $params['query_str'],
                    'recipient_type' => Constants::ROLE_TYPE_USER
                ])
                ->whereIn('recharge_type', Constants::$recharge_type)
                ->orderBy('id', 'desc')
                ->simplePaginate($params['page_size']);
            if ($transaction_flow->isEmpty()) {
                return TransactionFlow::where([
                        'recipient_id' => $params['query_str'],
                        'recipient_type' => Constants::ROLE_TYPE_USER
                    ])
                    ->whereIn('recharge_type', Constants::$recharge_type)
                    ->orderBy('id', 'desc')
                    ->simplePaginate($params['page_size']);
            }

            return $transaction_flow;
        }

        return TransactionFlow::where([
                'recipient_type' => Constants::ROLE_TYPE_USER
            ])
            ->whereIn('recharge_type', Constants::$recharge_type)
            ->whereBetween('created_at', [$start_time, $end_time])
            ->orderBy('id', 'desc')
            ->simplePaginate($params['page_size']);

    }
}