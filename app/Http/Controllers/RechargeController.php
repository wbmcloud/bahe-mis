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
        $this->validateRechargeParams($request);
        if ($this->attemptAgentRecharge($request)) {
            return $this->sendSuccessResponse();
        }

        return $this->sendFailResponse();
    }

    public function userRecharge(Request $request)
    {
        $this->validateRechargeParams($request);
        if ($this->attemptUserRecharge($request)) {
            return $this->sendSuccessResponse();
        }

        return $this->sendFailResponse();
    }

    protected function validateRechargeParams(Request $request)
    {
        $this->validate($request, [
            'user_name' => 'string|required',
            'num' => 'integer|required',
            'recharge_type' => 'integer|required',
            'code' => 'string'
        ]);
    }

    protected function attemptAgentRecharge($request)
    {
        $user_name = $request->input('user_name');
        $num = $request->input('num');
        $recharge_type = $request->input('recharge_type');
        $code = $request->input('code');

        $login_user_role = $this->checkAgentRechargeAuth();
        $recharge_user_role = $this->checkAgentRechargeRole($user_name);

        DB::beginTransaction();

        try {
            $transaction_flow = new TransactionFlow();
            $transaction_flow->initiator_id = Auth::id();
            $transaction_flow->initiator_name = Auth::user()->name;
            $transaction_flow->initiator_type = Constants::$recharge_role_type[$login_user_role['name']];
            $transaction_flow->recipient_id = $recharge_user_role['pivot']['user_id'];
            $transaction_flow->recipient_name = $user_name;
            $transaction_flow->recipient_type = Constants::$recharge_role_type[$recharge_user_role['name']];
            $transaction_flow->recharge_type = $recharge_type;
            $transaction_flow->num = $num;
            $transaction_flow->status = Constants::COMMON_ENABLE;
            $transaction_flow->save();

            $account = Accounts::where('user_name', $user_name)->first();
            if (empty($account)) {
                $account = new Accounts();
                $account->user_id = $recharge_user_role['pivot']['user_id'];
                $account->user_name = $user_name;
                $account->type = $recharge_type;
                $account->balance = $num;
                $account->total = $num;
                $account->save();
            } else {
                $account->balance += $num;
                $account->total += $num;
                $account->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error([
                'errno' => $e->getCode(),
                'errmsg' => $e->getMessage(),
            ]);
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
        $user_name = $request->input('user_name');
        $num = $request->input('num');
        $recharge_type = $request->input('recharge_type');

        $login_user_role = $this->checkAgentRechargeAuth();

        // TODO

        DB::beginTransaction();

        try {
            $transaction_flow = new TransactionFlow();
            $transaction_flow->initiator_id = Auth::id();
            $transaction_flow->initiator_name = Auth::user()->name;
            $transaction_flow->initiator_type = Constants::$recharge_role_type[$login_user_role['name']];
            $transaction_flow->recipient_id = $user_name;
            $transaction_flow->recipient_type = Constants::ROLE_TYPE_USER;
            $transaction_flow->recharge_type = $recharge_type;
            $transaction_flow->num = $num;
            $transaction_flow->status = Constants::COMMON_DISABLE;
            $transaction_flow->save();

            if ($login_user_role['name'] === Constants::ROLE_AGENT) {
                $account = Accounts::where([
                    'user_id' => Auth::id(),
                    'type' => $recharge_type,
                ])->first();
                if (empty($account) || $account->balance < $num) {
                    throw new SlException(SlException::ACCOUNT_BALANCE_NOT_ENOUGH);
                }
                $account->balance -= $num;
                $account->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error([
                'request' => $request->all(),
                'errno' => $e->getCode(),
                'errmsg' => $e->getMessage(),
            ]);
            throw new SlException(SlException::FAIL_CODE);
        }

        // 调用充值idip接口
        $command['command_type'] = Constants::IDIP_TYPE_RECHARGE;
        $command['account'] = $user_name;
        $command['player_id'] = '0';
        $command['count'] = $num;
        $serialize = Protobuf::pack($command);
        $res = Protobuf::unpackForResponse(TcpClient::callTcpService($serialize));
        if (empty($res) || $res['error_code']) {
            Log::error([
                'request' => $request->all(),
                'res' => $res,
            ]);
            $transaction_flow->recharge_fail_reason = json_encode($res);
        }
        $transaction_flow->status = Constants::COMMON_ENABLE;
        $transaction_flow->save();

        return true;
    }
}