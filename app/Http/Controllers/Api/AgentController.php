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
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller
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
        $user = User::find($this->params['id']);
        if (empty($user)) {
            throw new BaheException(BaheException::AGENT_NOT_EXIST_CODE);
        }
        return $user->toArray();
    }

    public function saveAgent(Request $request)
    {
        $user = User::find($this->params['id']);
        if (empty($user)) {
            throw new BaheException(BaheException::AGENT_NOT_EXIST_CODE);
        }
        !empty($this->params['city_id']) && ($user->city_id = $this->params['city_id']);
        !empty($this->params['name']) && ($user->name = $this->params['name']);
        !empty($this->params['invite_code']) && ($user->invite_code = $this->params['invite_code']);
        !empty($this->params['uin']) && ($user->uin = $this->params['uin']);
        !empty($this->params['wechat']) && ($user->wechat = $this->params['wechat']);
        !empty($this->params['uin_group']) && ($user->uin_group = $this->params['uin_group']);
        !empty($this->params['tel']) && ($user->tel = $this->params['tel']);
        !empty($this->params['bank_card']) && ($user->bank_card = $this->params['bank_card']);
        !empty($this->params['id_card']) && ($user->id_card = $this->params['id_card']);
        $user->save();
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