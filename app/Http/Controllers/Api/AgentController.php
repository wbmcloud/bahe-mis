<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers\Api;

use App\Common\Constants;
use App\Common\Utils;
use App\Exceptions\SlException;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends ApiBaseController
{
    public function banAgent(Request $request)
    {
        $user_id = $request->input('id');
        $user = User::findOrFail($user_id);
        $user->status = Constants::COMMON_DISABLE;
        $user->save();
        return Utils::sendJsonSuccessResponse();
    }

    public function unBanAgent(Request $request)
    {
        $user_id = $request->input('id');
        $user = User::findOrFail($user_id);
        $user->status = Constants::COMMON_ENABLE;
        $user->save();
        return Utils::sendJsonSuccessResponse();
    }

    public function addAgent(Request $request)
    {
        $user_id = $request->input('id');
        $user = User::findOrFail($user_id);
        $user->status = Constants::COMMON_ENABLE;
        $user->save();
        return Utils::sendJsonSuccessResponse();
    }

    public function agentInfo()
    {
        $user = User::find($this->params['id']);
        if (empty($user)) {
            throw new SlException(SlException::AGENT_NOT_EXSIST_CODE);
        }
        return Utils::sendJsonResponse(SlException::SUCCESS_CODE, '', $user->toArray());
    }

    protected function validateAgentParams(Request $request)
    {
        $this->validate($request, [
            'invite_code' => 'integer|nullable',
            'uin' => 'integer|nullable',
            'wechat' => 'string|nullable',
            'uin_group' => 'string|nullable',
            'tel' => 'integer|nullable',
            'bank_card' => 'string|nullable',
            'id_card' => 'string|nullable',
        ]);
    }

    public function saveAgent(Request $request)
    {
        $this->validateAgentParams($request);
        $user = User::find($this->params['id']);
        if (empty($user)) {
            throw new SlException(SlException::AGENT_NOT_EXSIST_CODE);
        }
        !empty($this->params['city_id']) && ($user->city_id = $this->params['city_id']);
        !empty($this->params['invite_code']) && ($user->invite_code = $this->params['invite_code']);
        !empty($this->params['uin']) && ($user->uin = $this->params['uin']);
        !empty($this->params['wechat']) && ($user->wechat = $this->params['wechat']);
        !empty($this->params['uin_group']) && ($user->uin_group = $this->params['uin_group']);
        !empty($this->params['tel']) && ($user->tel = $this->params['tel']);
        !empty($this->params['bank_card']) && ($user->bank_card = $this->params['bank_card']);
        !empty($this->params['id_card']) && ($user->id_card = $this->params['id_card']);
        $user->save();
        return Utils::sendJsonSuccessResponse();
    }

    public function agentList()
    {
        $page = isset($this->params['page']) ? $this->params['page'] : Constants::DEFAULT_PAGE;
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] : Constants::DEFAULT_PAGE_SIZE;
        $users = Role::where('id', Constants::ROLE_TYPE_AGENT)
            ->first()
            ->users()
            ->where('status', Constants::COMMON_ENABLE)
            ->forPage($page, $page_size)
            ->get()
            ->toArray();
        $users = array_map(function($user) {
            return ['id' => $user['id'],
                'name' => $user['name'],
                'created_at' => $user['created_at']];
        }, $users);
        $total_count = Role::where('id', Constants::ROLE_TYPE_AGENT)
            ->first()
            ->users()
            ->where('status', Constants::COMMON_ENABLE)
            ->count();
        return Utils::sendJsonSuccessResponse([
            'list' => $users,
            'total_count' => $total_count,
        ]);
    }

    public function resetPassword()
    {
        $user = User::find($this->params['id']);
        if (empty($user)) {
            throw new SlException(SlException::AGENT_NOT_EXSIST_CODE);
        }

        // 重置密码
        $user->password = bcrypt($this->params['password']);
        $user->save();
        return Utils::sendJsonSuccessResponse();
    }
}