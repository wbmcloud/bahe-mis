<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers;

use App\Common\Constants;
use App\Exceptions\SlException;
use App\Models\GeneralAgents;
use App\Models\InviteCode;
use App\Models\Role;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GeneralAgentController extends Controller
{

    public function agentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        if (isset($this->params['query_str']) && !empty($this->params['query_str'])) {
            // 分析query_str类型
            if (is_numeric($this->params['query_str']) &&
                (strlen($this->params['query_str']) == Constants::INVITE_CODE_LENGTH)) {
                // 邀请码查询
                $users = GeneralAgents::where('status', Constants::COMMON_ENABLE)
                    ->where('invite_code', $this->params['query_str'])
                    ->paginate($page_size);
            } else {
                // 姓名查询
                $users = GeneralAgents::where('status', Constants::COMMON_ENABLE)
                    ->where('name', $this->params['query_str'])
                    ->paginate($page_size);
            }
        } else {
            $users = GeneralAgents::where('status', Constants::COMMON_ENABLE)
                ->paginate($page_size);
        }

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
        // 校验邀请码合法性
        $invite_code = InviteCode::where('invite_code', $this->params['invite_code'])->first();
        if (empty($invite_code)) {
            throw new SlException(SlException::INVITE_CODE_NOT_VALID_CODE);
        }
        DB::beginTransaction();

        try {
            $general_agent->name = $this->params['name'];
            $general_agent->invite_code = $this->params['invite_code'];
            !empty($this->params['tel']) && ($general_agent->tel = $this->params['tel']);
            !empty($this->params['bank_card']) && ($general_agent->bank_card = $this->params['bank_card']);
            !empty($this->params['id_card']) && ($general_agent->id_card = $this->params['id_card']);
            $general_agent->save();

            $invite_code->is_used = Constants::COMMON_ENABLE;
            $invite_code->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new SlException(SlException::FAIL_CODE);
        }
        return true;
    }


    public function inviteCode()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] : Constants::DEFAULT_PAGE_SIZE;
        $codes = InviteCode::paginate($page_size);
        return view('general_agent.invite_code', [
            'codes' => $codes,
        ]);
    }

    public function banAgentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        if (isset($this->params['query_str']) && !empty($this->params['query_str'])) {
            // 分析query_str类型
            if (is_numeric($this->params['query_str']) &&
                (strlen($this->params['query_str']) == Constants::INVITE_CODE_LENGTH)) {
                // 邀请码查询
                $users = GeneralAgents::where('status', Constants::COMMON_DISABLE)
                    ->where('invite_code', $this->params['query_str'])
                    ->paginate($page_size);
            } else {
                // 姓名查询
                $users = GeneralAgents::where('status', Constants::COMMON_DISABLE)
                    ->where('name', $this->params['query_str'])
                    ->paginate($page_size);
            }
        } else {
            $users = GeneralAgents::where('status', Constants::COMMON_DISABLE)
                ->paginate($page_size);
        }

        return view('general_agent.banlist', [
            'agents' => $users
        ]);
    }

    public function agentRechargeList(Request $request)
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        $start_time = isset($this->params['start_date']) ? $this->params['start_date'] : Carbon::today()->toDateString();
        $end_time = isset($this->params['end_date']) ? $this->params['end_date'] : Carbon::tomorrow()->toDateString();

        // 参数校验
        $this->validate($request, [
            'invite_code' => 'required|digits:7',
            /*'start_time' => 'required|date',
            'end_time' => 'required|date'*/
        ]);
        $users = User::where([
            'invite_code' => $this->params['invite_code'],
        ])->get()->toArray();
        if (empty($users) || ($start_time > $end_time)) {
            $recharge_flows = new LengthAwarePaginator([], 0, $page_size);
        } else {
            $recharge_flows = TransactionFlow::whereIn('recipient_id', array_column($users, 'id'))
                ->whereBetween('created_at', [$start_time, $end_time])
                ->paginate($page_size);
        }

        return view('general_agent.recharge', [
            'recharge_flows' => $recharge_flows
        ]);
    }
}