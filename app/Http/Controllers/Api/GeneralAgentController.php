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
use App\Models\GeneralAgents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class GeneralAgentController extends ApiBaseController
{
    public function banAgent(Request $request)
    {
        $user_id = $request->input('id');
        $user = GeneralAgents::findOrFail($user_id);
        $user->status = Constants::COMMON_DISABLE;
        $user->save();
        return Utils::sendJsonSuccessResponse();
    }

    public function unBanAgent(Request $request)
    {
        $user_id = $request->input('id');
        $user = GeneralAgents::findOrFail($user_id);
        $user->status = Constants::COMMON_ENABLE;
        $user->save();
        return Utils::sendJsonSuccessResponse();
    }

    public function agentInfo()
    {
        $general_agent = GeneralAgents::find($this->params['id']);
        if (empty($general_agent)) {
            throw new SlException(SlException::AGENT_NOT_EXSIST_CODE);
        }
        return Utils::sendJsonResponse(SlException::SUCCESS_CODE, '', $general_agent->toArray());
    }

    public function saveAgent(Request $request)
    {
        $user = GeneralAgents::find($this->params['id']);
        if (empty($user)) {
            throw new SlException(SlException::AGENT_NOT_EXSIST_CODE);
        }
        !empty($this->params['tel']) && ($user->tel = $this->params['tel']);
        !empty($this->params['bank_card']) && ($user->bank_card = $this->params['bank_card']);
        !empty($this->params['id_card']) && ($user->id_card = $this->params['id_card']);
        $user->save();
        return Utils::sendJsonSuccessResponse();
    }

}