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
use App\Logic\UserLogic;
use App\Models\InviteCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GeneralAgentController extends Controller
{

    public function agentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $general_agent_logic = new GeneralAgentLogic();
        $user_logic = new UserLogic();

        $users = $general_agent_logic->getGeneralAgentList($this->params, $page_size);
        $agents_count = $general_agent_logic->getAgentCount(
            array_column($users->toArray()['data'], 'code'));
        $agents_count = array_column($agents_count->toArray(), null, 'invite_code');
        $first_agents_count = $general_agent_logic->getFirstAgentCount(
            array_column($users->toArray()['data'], 'code'));
        $first_agents_count = array_column($first_agents_count->toArray(), null, 'invite_code');
        $cities = $user_logic->getOpenCities();

        return [
            'agents'       => $users,
                'agents_count' => $agents_count,
            'first_agents_count' => $first_agents_count,
            'cities'       => $cities,
        ];
    }

    public function inviteCode()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        $codes = InviteCode::where('type', Constants::INVITE_CODE_TYPE_GENERAL_AGENT)
            ->orderBy('invite_code')->simplePaginate($page_size);

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

        if (Auth::user()->hasRole(Constants::ROLE_GENERAL_AGENT)) {
            if (substr($this->params['invite_code'], 0, Constants::INVITE_CODE_LENGTH) !==
                Auth::user()->code) {
                $this->params['invite_code'] = Auth::user()->code;
            }
        }

        $recharge_flows      = $general_agent_logic->getAgentRechargeList($this->params['invite_code'], $start_time,
            $end_time, $page_size);

        return [
            'recharge_flows' => $recharge_flows
        ];
    }

    public function firstAgentIncomeList()
    {
        $page_size           = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        $general_agent_logic = new GeneralAgentLogic();

        if (Auth::user()->hasRole(Constants::ROLE_GENERAL_AGENT)) {
            $this->params['invite_code'] = Auth::user()->code;
        }

        $this->params['page_size'] = $page_size;
        $first_agents = $general_agent_logic->getFirstAgentIncomeList($this->params);

        return [
            'first_agents' => $first_agents
        ];
    }

    public function weekCashOrderList()
    {
        $page_size  = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $general_agent_logic = new GeneralAgentLogic();

        return [
            'cash_orders' => $general_agent_logic->getWeekCashOrder(Constants::AGENT_LEVEL_GENERAL, $page_size)
        ];
    }

    public function income()
    {
        if (Auth::user()->hasRole(Constants::$admin_role)) {
            $user = User::find($this->params['agent_id']);
        } else {
            $user = Auth::user();
        }

        $general_agent_logic = new GeneralAgentLogic();

        return [
            'income_stat' => $general_agent_logic->getCurrentAgentIncomeStat($user)
        ];
    }

    public function sale()
    {
        $page_size  = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        if (Auth::user()->hasRole(Constants::$admin_role)) {
            $login_user = User::find($this->params['agent_id']);
        } else {
            $login_user = Auth::user();
        }

        $general_agent_logic = new GeneralAgentLogic();

        if ($this->params['type'] == Constants::ROLE_TYPE_AGENT) {
            $level_agent_sale_amount_list = $general_agent_logic->getLevelAgentSaleAmountDetail($login_user->id, $page_size);
        } elseif ($this->params['type'] == Constants::ROLE_TYPE_FIRST_AGENT) {
            $start_of_week = Carbon::now()->startOfWeek()->toDateTimeString();
            $end_time = Carbon::now()->toDateTimeString();

            $level_agent_sale_amount_list = $general_agent_logic->getFirstAgentIncomeList([
                'invite_code' => $login_user->code,
                'start_time' => $start_of_week,
                'end_time' => $end_time,
                'page_size' => $page_size
            ]);
        }

        $agent_ids = array_column($level_agent_sale_amount_list->toArray()['data'], 'id');
        $agents = $general_agent_logic->getAgentInfoByIds($agent_ids);

        return [
            'level_agent_sale_amount_list' => $level_agent_sale_amount_list,
            'agents' => $agents
        ];
    }

    public function incomeHistory()
    {
        $page_size  = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        if (Auth::user()->hasRole(Constants::$admin_role)) {
            $agent_id = User::find($this->params['agent_id']);
        } else {
            $agent_id = Auth::user();
        }

        $general_agent_logic = new GeneralAgentLogic();

        return [
            'history_income_list' => $general_agent_logic->getLevelAgentCashOrderList($agent_id, $page_size)
        ];
    }
}