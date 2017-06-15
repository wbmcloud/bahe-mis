<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers;

use App\Common\Constants;
use App\Logic\GeneralAgentLogic;
use App\Models\InviteCode;
use Carbon\Carbon;

class GeneralAgentController extends Controller
{

    public function agentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $general_agent_logic = new GeneralAgentLogic();
        $users = $general_agent_logic->getGeneralAgentList($this->params, $page_size);

        return [
            'agents' => $users,
        ];
    }

    public function addAgentForm()
    {
        return [];
    }

    public function addAgent()
    {
        $general_agent_logic = new GeneralAgentLogic();
        return $general_agent_logic->addGeneralAgent($this->params);
    }

    public function inviteCode()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        $codes = InviteCode::paginate($page_size);
        return [
            'codes' => $codes,
        ];
    }

    public function banAgentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $general_agent_logic = new GeneralAgentLogic();
        $users = $general_agent_logic->getGeneralAgentList($this->params, $page_size,
            Constants::COMMON_DISABLE);

        return [
            'agents' => $users
        ];
    }

    public function agentRechargeList()
    {
        $page_size  = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        $start_time = isset($this->params['start_date']) ? $this->params['start_date'] : Carbon::today()->toDateString();
        $end_time   = isset($this->params['end_date']) ? $this->params['end_date'] : Carbon::tomorrow()->toDateString();

        $general_agent_logic = new GeneralAgentLogic();
        $recharge_flows      = $general_agent_logic->getAgentRechargeList($this->params['invite_code'], $start_time,
            $end_time, $page_size);

        return [
            'recharge_flows' => $recharge_flows
        ];
    }
}