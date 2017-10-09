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
use App\Models\Accounts;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AccountLogic extends BaseLogic
{
    public function getAgentBalance()
    {
        $user = Auth::user();
        if (empty($user)) {
            return redirect()->intended('login');
        }
        return Accounts::where('user_id', $user->id)->first();
    }

    /**
     * @param $user_name
     * @return mixed
     */
    public function getAccountByUserName($user_name)
    {
        return User::where([
            'user_name' => $user_name,
        ])->first()->account()->first();
    }

    /**
     * @param $params
     * @return Accounts
     */
    public function createAccount($params)
    {
        $account            = new Accounts();

        if (isset($params['recharge_type'])) {
            switch ($params['recharge_type']) {
                case COMMAND_TYPE::COMMAND_TYPE_RECHARGE:
                    $account->diamond_balance = $params['num'];
                    $account->diamond_total = $params['num'];
                    break;
                case COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD:
                    $account->card_balance = $params['num'];
                    $account->card_total = $params['num'];
                    break;
                case COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU:
                    $account->bean_balance = $params['num'];
                    $account->bean_total = $params['num'];
                    break;
            }
        }
        $account->user_id   = $params['user_id'];
        $account->save();

        return $account;
    }

    public function saveAccount($account, $params)
    {
        $num = $params['num'] + intval($params['give_num']);
        if (isset($params['recharge_type'])) {
            switch ($params['recharge_type']) {
                case COMMAND_TYPE::COMMAND_TYPE_RECHARGE:
                    $account->diamond_balance += $num;
                    $account->diamond_total += $num;
                    break;
                case COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD:
                    $account->card_balance += $num;
                    $account->card_total += $num;
                    break;
                case COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU:
                    $account->bean_balance += $num;
                    $account->bean_total += $num;
                    break;
            }
        }
        $account->save();

        return $account;
    }
    /**
     * @param $user_name
     * @param $type
     * @param $num
     * @return mixed
     * @throws BaheException
     */
    public function reduceBalance($user_name, $type, $num)
    {
        $account = $this->getAccountByUserName($user_name);
        switch ($type) {
            case COMMAND_TYPE::COMMAND_TYPE_RECHARGE:
                $condition = empty($account) || $account->diamond_balance < $num;
                $account->diamond_balance -= $num;
                break;
            case COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD:
                $condition = empty($account) || $account->card_balance < $num;
                $account->card_balance -= $num;
                break;
            case COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU:
                $condition = empty($account) || $account->bean_balance < $num;
                $account->bean_balance -= $num;
                break;
        }
        if ($condition) {
            throw new BaheException(BaheException::ACCOUNT_BALANCE_NOT_ENOUGH);
        }
        $account->save();

        return $account;
    }
}