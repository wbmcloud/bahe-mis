<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers\Api;

use App\Common\Constants;
use App\Exceptions\SlException;
use App\Http\Controllers\Controller;
use App\Models\GeneralAgents;
use App\Models\TransactionFlow;
use Illuminate\Http\Request;

class GeneralAgentController extends Controller
{
    public function banAgent(Request $request)
    {
        $user_id = $request->input('id');
        $user = GeneralAgents::findOrFail($user_id);
        $user->status = Constants::COMMON_DISABLE;
        $user->save();
        return [];
    }

    public function unBanAgent(Request $request)
    {
        $user_id = $request->input('id');
        $user = GeneralAgents::findOrFail($user_id);
        $user->status = Constants::COMMON_ENABLE;
        $user->save();
        return [];
    }

    public function agentInfo()
    {
        $general_agent = GeneralAgents::find($this->params['id']);
        if (empty($general_agent)) {
            throw new SlException(SlException::AGENT_NOT_EXSIST_CODE);
        }
        return $general_agent->toArray();
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
        return [];
    }

    public function delAgentFlow(Request $request)
    {
        $this->validate($request, [
            'id' => 'integer|required',
        ]);
        TransactionFlow::find($this->params['id'])->delete();
        return [];
    }

}