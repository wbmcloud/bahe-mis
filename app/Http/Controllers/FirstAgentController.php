<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers;

use App\Common\Constants;
use App\Logic\FirstAgentLogic;
use App\Logic\UserLogic;
use App\Models\InviteCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FirstAgentController extends Controller
{

    public function agentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $general_agent_logic = new FirstAgentLogic();
        $user_logic = new UserLogic();

        $users = $general_agent_logic->getGeneralAgentList($this->params, $page_size);
        $cities = $user_logic->getOpenCities();

        return [
            'agents' => $users,
            'cities' => $cities,
        ];
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

        $general_agent_logic = new FirstAgentLogic();
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

        $general_agent_logic = new FirstAgentLogic();

        if (Auth::user()->hasRole(Constants::ROLE_FIRST_AGENT)) {
            $recharge_flows      = $general_agent_logic->getAgentRechargeList(Auth::user()->invite_code, $start_time,
                $end_time, $page_size);
        } else {
            $recharge_flows      = $general_agent_logic->getAgentRechargeList($this->params['invite_code'], $start_time,
                $end_time, $page_size);
        }

        return [
            'recharge_flows' => $recharge_flows
        ];
    }

    public function currentWeekCashOrderList()
    {
        $page_size  = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $first_agent_logic = new FirstAgentLogic();

        return [
            'cash_orders' => $first_agent_logic->getLastWeekCashOrder($page_size)
        ];
    }

}