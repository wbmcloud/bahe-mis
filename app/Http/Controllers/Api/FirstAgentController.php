<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers\Api;

use App\Common\Constants;
use App\Exceptions\BaheException;
use App\Http\Controllers\Controller;
use App\Models\CashOrder;
use App\Models\GeneralAgents;
use App\Models\TransactionFlow;
use App\Models\User;
use Illuminate\Http\Request;

class FirstAgentController extends Controller
{
    public function banAgent(Request $request)
    {
        $user_id = $request->input('id');
        $user = User::findOrFail($user_id);
        $user->status = Constants::COMMON_DISABLE;
        $user->save();
        return [];
    }

    public function unBanAgent(Request $request)
    {
        $user_id = $request->input('id');
        $user = User::findOrFail($user_id);
        $user->status = Constants::COMMON_ENABLE;
        $user->save();
        return [];
    }

    public function agentInfo()
    {
        $first_agent = User::find($this->params['id']);
        if (empty($first_agent)) {
            throw new BaheException(BaheException::AGENT_NOT_EXIST_CODE);
        }
        return $first_agent->toArray();
    }

    public function saveAgent()
    {
        $user = User::find($this->params['id']);
        if (empty($user)) {
            throw new BaheException(BaheException::AGENT_NOT_EXIST_CODE);
        }
        !empty($this->params['tel']) && ($user->tel = $this->params['tel']);
        !empty($this->params['bank_card']) && ($user->bank_card = $this->params['bank_card']);
        !empty($this->params['id_card']) && ($user->id_card = $this->params['id_card']);
        $user->save();
        return [];
    }

    public function delAgentFlow()
    {
        TransactionFlow::find($this->params['id'])->delete();
        return [];
    }

    public function confirmCashOrder()
    {
        $cash_order = CashOrder::find($this->params['id']);

        if (empty($cash_order)) {
            throw new BaheException(BaheException::CASH_ORDER_NOT_FOUND_CODE);
        }

        $cash_order->status = Constants::COMMON_ENABLE;
        $cash_order->save();
        return [];
    }

    public function resetPassword()
    {
        $user = User::find($this->params['id']);
        if (empty($user)) {
            throw new BaheException(BaheException::AGENT_NOT_EXIST_CODE);
        }

        // 重置密码
        $user->password = bcrypt($this->params['password']);
        $user->save();
        return [];
    }

}