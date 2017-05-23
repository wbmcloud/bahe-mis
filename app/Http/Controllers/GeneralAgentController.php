<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers;

use App\Common\Constants;
use App\Models\GeneralAgents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GeneralAgentController extends Controller
{

    public function agentList()
    {
        // $page = isset($this->params['page']) ? $this->params['page'] : Constants::DEFAULT_PAGE;
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] : Constants::DEFAULT_PAGE_SIZE;
        $users = GeneralAgents::where('status', Constants::COMMON_ENABLE)
            ->paginate($page_size);
        return view('general_agent.list', [
            'agents' => $users,
        ]);
    }

    public function addAgentForm()
    {
        return view('general_agent.add');
    }

    public function addAgent(Request $request)
    {
        $this->validateParams($request);

        if ($this->attemptAddAgent()) {
            return $this->sendSuccessResponse();
        }

        return $this->sendFailResponse();
    }

    protected function validateParams(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'invite_code' => 'integer|required',
            'tel' => 'integer|required',
            'bank_card' => 'integer|nullable',
            'id_card' => 'integer|nullable',
        ]);
    }

    protected function attemptAddAgent()
    {
        $general_agent = new GeneralAgents();
        !empty($this->params['name']) && ($general_agent->name = $this->params['name']);
        !empty($this->params['invite_code']) && ($general_agent->invite_code = $this->params['invite_code']);
        !empty($this->params['tel']) && ($general_agent->tel = $this->params['tel']);
        !empty($this->params['bank_card']) && ($general_agent->bank_card = $this->params['bank_card']);
        !empty($this->params['id_card']) && ($general_agent->id_card = $this->params['id_card']);
        $general_agent->save();
        return true;
    }

}