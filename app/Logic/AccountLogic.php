<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

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
}