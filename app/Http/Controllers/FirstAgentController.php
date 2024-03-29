<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers;

use App\Common\Constants;
use App\Exceptions\BaheException;
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

        $first_agent_logic = new FirstAgentLogic();
        $user_logic = new UserLogic();

        $users = $first_agent_logic->getFirstAgentList($this->params, $page_size);
        $agents_count = $first_agent_logic->getAgentCount(
            array_column($users->toArray()['data'], 'code_id'));
        $agents_count = array_column($agents_count->toArray(), null, 'invite_code_id');
        $cities = $user_logic->getOpenCities();

        return [
            'agents'       => $users,
            'agents_count' => $agents_count,
            'cities'       => $cities,
        ];
    }

    public function inviteCode()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        $codes = InviteCode::where('type', Constants::INVITE_CODE_TYPE_FIRST_AGENT)
            ->orderBy('id')->simplePaginate($page_size);

        return [
            'codes' => $codes,
        ];
    }

    public function banAgentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $first_agent_logic = new FirstAgentLogic();
        $users = $first_agent_logic->getFirstAgentList($this->params, $page_size,
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

        if (empty($this->params['invite_code_id'])) {
            throw new BaheException(BaheException::INVITE_CODE_NOT_VALID_CODE);
        }

        $first_agent_logic = new FirstAgentLogic();

        if (Auth::user()->hasRole(Constants::ROLE_FIRST_AGENT)) {
            $recharge_flows      = $first_agent_logic->getAgentRechargeList(Auth::user()->code_id, $start_time,
                $end_time, $page_size);
        } else {
            $recharge_flows      = $first_agent_logic->getAgentRechargeList($this->params['invite_code_id'], $start_time,
                $end_time, $page_size);
        }

        return [
            'recharge_flows' => $recharge_flows
        ];
    }

    public function weekCashOrderList()
    {
        $page_size  = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $first_agent_logic = new FirstAgentLogic();

        return [
            'cash_orders' => $first_agent_logic->getWeekCashOrder(Constants::AGENT_LEVEL_FIRST, $page_size)
        ];
    }

    public function income()
    {
        $first_agent_logic = new FirstAgentLogic();

        if (Auth::user()->hasRole(Constants::$admin_role)) {
            $agent_id = $this->params['agent_id'];
        } else {
            $agent_id = Auth::id();
        }

        return [
            'income_stat' => $first_agent_logic->getCurrentAgentIncomeStat($agent_id)
        ];
    }

    public function sale()
    {
        $page_size  = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        if (Auth::user()->hasRole(Constants::$admin_role)) {
            $agent_id = $this->params['agent_id'];
        } else {
            $agent_id = Auth::id();
        }

        $first_agent_logic = new FirstAgentLogic();

        $level_agent_sale_amount_list = $first_agent_logic->getLevelAgentSaleAmountDetail($agent_id, $page_size);
        $agent_ids = array_column($level_agent_sale_amount_list->toArray()['data'], 'user_id');
        $agents = $first_agent_logic->getAgentInfoByIds($agent_ids);

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
            $agent_id = $this->params['agent_id'];
        } else {
            $agent_id = Auth::id();
        }

        $first_agent_logic = new FirstAgentLogic();

        return [
            'history_income_list' => $first_agent_logic->getLevelAgentCashOrderList($agent_id, $page_size)
        ];
    }
}