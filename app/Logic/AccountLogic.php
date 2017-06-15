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
use App\Models\Accounts;
use Illuminate\Support\Facades\Auth;

class AccountLogic extends BaseLogic
{
    public function getAgentBalance()
    {
        $user = Auth::user();
        if (empty($user)) {
            return redirect()->intended('login');
        }
        return Accounts::where('user_id', $user->id)->get()->toArray();
    }

    /**
     * @param $user_name
     * @param $recharge_type
     * @return mixed
     */
    public function getAccountByUserNameAndType($user_name, $recharge_type)
    {
        return Accounts::where([
            'user_name' => $user_name,
            'type' => $recharge_type,
        ])->first();
    }

    /**
     * @param $params
     * @return Accounts
     */
    public function createAccount($params)
    {
        $account            = new Accounts();
        $account->user_id   = $params['user_id'];
        $account->user_name = $params['user_name'];
        $account->type      = $params['recharge_type'];
        $account->balance   = $params['num'];
        $account->total     = $params['num'];
        $account->save();

        return $account;
    }

    /**
     * @param $user_name
     * @param $type
     * @param $num
     * @return mixed
     * @throws SlException
     */
    public function reduceBalance($user_name, $type, $num)
    {
        $account = $this->getAccountByUserNameAndType($user_name, $type);
        if (empty($account) || $account->balance < Constants::OPEN_ROOM_CARD_REDUCE) {
            throw new SlException(SlException::ACCOUNT_BALANCE_NOT_ENOUGH);
        }
        $account->balance -= $num;
        $account->save();

        return $account;
    }
}