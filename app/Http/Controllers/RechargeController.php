<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/11
 * Time: 下午10:55
 */
namespace App\Http\Controllers;

use App\Common\Constants;
use App\Exceptions\SlException;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Library\Protobuf\Protobuf;
use App\Library\TcpClient;
use App\Logic\AccountLogic;
use App\Models\Accounts;
use App\Models\TransactionFlow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RechargeController extends Controller
{
    public function showAgentRechargeForm()
    {
        return view('recharge.agent');
    }

    public function showUserRechargeForm()
    {
        $account_logic = new AccountLogic();
        $accounts = $account_logic->getAgentBalance();

        return view('recharge.user', [
            'accounts' => $accounts
        ]);
    }

    public function agentRecharge(Request $request)
    {
        $this->validateAgentRechargeParams($request);
        if ($this->attemptAgentRecharge($request)) {
            return $this->sendSuccessResponse();
        }

        return $this->sendFailResponse();
    }

    public function userRecharge(Request $request)
    {
        $this->validateUserRechargeParams($request);
        if ($this->attemptUserRecharge($request)) {
            return $this->sendSuccessResponse();
        }

        return $this->sendFailResponse();
    }

    protected function validateAgentRechargeParams(Request $request)
    {
        $this->validate($request, [
            'user_name' => 'string|required',
            'num' => 'integer|required',
            'recharge_type' => ['required', Rule::in([
                COMMAND_TYPE::COMMAND_TYPE_RECHARGE,
                COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU
            ])],
            'code' => 'string'
        ]);
    }

    protected function validateUserRechargeParams(Request $request)
    {
        $this->validate($request, [
            'role_id' => 'integer|required',
            'num' => 'integer|required',
            'recharge_type' => ['required', Rule::in([
                COMMAND_TYPE::COMMAND_TYPE_RECHARGE,
                COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU
            ])]
        ]);
    }

    protected function attemptAgentRecharge(Request $request)
    {
        $login_user_role = $this->checkAgentRechargeAuth();
        $recharge_user_role = $this->checkAgentRechargeRole($this->params['user_name']);

        DB::beginTransaction();

        try {
            $transaction_flow = new TransactionFlow();
            $transaction_flow->initiator_id = Auth::id();
            $transaction_flow->initiator_name = Auth::user()->name;
            $transaction_flow->initiator_type = Constants::$recharge_role_type[$login_user_role['name']];
            $transaction_flow->recipient_id = $recharge_user_role['pivot']['user_id'];
            $transaction_flow->recipient_name = $this->params['user_name'];
            $transaction_flow->recipient_type = Constants::$recharge_role_type[$recharge_user_role['name']];
            $transaction_flow->recharge_type = $this->params['recharge_type'];
            $transaction_flow->num = $this->params['num'];
            $transaction_flow->status = Constants::COMMON_ENABLE;
            $transaction_flow->save();

            $account = Accounts::where([
                'user_name' => $this->params['user_name'],
                'type' => $this->params['recharge_type'],
                ])
                ->first();
            if (empty($account)) {
                $account = new Accounts();
                $account->user_id = $recharge_user_role['pivot']['user_id'];
                $account->user_name = $this->params['user_name'];
                $account->type = $this->params['recharge_type'];
                $account->balance = $this->params['num'];
                $account->total = $this->params['num'];
                $account->save();
            } else {
                $account->balance += $this->params['num'];
                $account->total += $this->params['num'];
                $account->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new SlException(SlException::FAIL_CODE);
        }

        return true;
    }

    protected function checkAgentRechargeRole($user_name)
    {
        $user = User::where('name', $user_name)->first();
        if (empty($user)) {
            throw new SlException(SlException::USER_NOT_EXSIST_CODE);
        }
        $role = $user->roles()->first()->toArray();
        if (empty($role)) {
            throw new SlException(SlException::ROLE_NOT_EXSIST_CODE);
        }
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
        $role = $user->roles()->first()->toArray();
        if (empty($role)) {
            throw new SlException(SlException::ROLE_NOT_EXSIST_CODE);
        }
        if ($role['name'] === Constants::ROLE_TYPE_AGENT) {
            throw new SlException(SlException::AGENT_NOT_RECHARGE_FOR_AGENT_CODE);
        }
        return $role;
    }

    protected function attemptUserRecharge(Request $request)
    {
        $is_recharged = true;
        $login_user_role = $this->checkAgentRechargeAuth();

        DB::beginTransaction();

        try {
            if ($login_user_role['name'] === Constants::ROLE_AGENT) {
                $account = Accounts::where([
                    'user_id' => Auth::id(),
                    'type' => $this->params['recharge_type'],
                ])->first();
                if (empty($account) || $account->balance < $this->params['num']) {
                    throw new SlException(SlException::ACCOUNT_BALANCE_NOT_ENOUGH);
                }
                $account->balance -= $this->params['num'];
                $account->save();
            }

            // 调用idip进行充值
            $inner_meta_register_srv = Protobuf::packRegisterInnerMeta();
            $register_res = TcpClient::callTcpService($inner_meta_register_srv, true);
            if ($register_res !== $inner_meta_register_srv) {
                throw new SlException(SlException::GMT_SERVER_REGISTER_FAIL_CODE);
            }
            // 调用idip进行充值
            if ($this->params['recharge_type'] == COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD) {
                $command['item_id'] = Constants::ROOM_CARD_ITEM_ID;
            }
            $command['command_type'] = $this->params['recharge_type'];
            $command['player_id'] = $this->params['role_id'];
            $command['count'] = $this->params['num'];
            $inner_meta_command = Protobuf::packCommandInnerMeta($command);
            $command_res = Protobuf::unpackForResponse(TcpClient::callTcpService($inner_meta_command));
            if ($command_res['error_code'] != 0) {
                throw new SlException(SlException::GMT_SERVER_RECHARGE_FAIL_CODE);
            }

            DB::commit();
        } catch (\Exception $e) {
            $is_recharged = false;
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
                    'error_msg' => $e->getMessage(),
                ]);
            }
        }

        $transaction_flow = new TransactionFlow();
        $transaction_flow->initiator_id = Auth::id();
        $transaction_flow->initiator_name = Auth::user()->name;
        $transaction_flow->initiator_type = Constants::$recharge_role_type[$login_user_role['name']];
        $transaction_flow->recipient_id = $this->params['role_id'];
        $transaction_flow->recipient_type = Constants::ROLE_TYPE_USER;
        $transaction_flow->recharge_type = $this->params['recharge_type'];
        $transaction_flow->num = $this->params['num'];

        if ($is_recharged) {
            $transaction_flow->status = Constants::COMMON_ENABLE;
        } else {
            $transaction_flow->recharge_fail_reason = $recharge_fail_reason;
        }
        $transaction_flow->save();

        if (!$is_recharged) {
            throw new SlException(SlException::GMT_SERVER_RECHARGE_FAIL_CODE);
        }

        return true;
    }
}