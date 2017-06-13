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
use App\Library\Protobuf\COMMAND_TYPE;
use App\Library\Protobuf\Protobuf;
use App\Library\TcpClient;
use App\Logic\UserLogic;
use App\Models\Accounts;
use App\Models\City;
use App\Models\Role;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{

    public function agentList()
    {
        // $page = isset($this->params['page']) ? $this->params['page'] : Constants::DEFAULT_PAGE;
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] : Constants::DEFAULT_PAGE_SIZE;

        if (isset($this->params['query_str']) && !empty($this->params['query_str'])) {
            $users = Role::where('id', Constants::ROLE_TYPE_AGENT)
                ->first()
                ->users()
                ->where('status', Constants::COMMON_ENABLE)
                ->where('name', $this->params['query_str'])
                ->paginate($page_size);
        } else {
            $users = Role::where('id', Constants::ROLE_TYPE_AGENT)
                ->first()
                ->users()
                ->where('status', Constants::COMMON_ENABLE)
                ->paginate($page_size);
        }
        return view('agent.list', [
            'cities' => (new UserLogic())->getAllOpenCities(),
            'agents' => $users,
        ]);
    }

    public function banAgentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        if (isset($this->params['query_str']) && !empty($this->params['query_str'])) {
            $users = Role::where('id', Constants::ROLE_TYPE_AGENT)
                ->first()
                ->users()
                ->where('status', Constants::COMMON_DISABLE)
                ->where('name', $this->params['query_str'])
                ->paginate($page_size);
        } else {
            $users = Role::where('id', Constants::ROLE_TYPE_AGENT)
                ->first()
                ->users()
                ->where('status', Constants::COMMON_DISABLE)
                ->paginate($page_size);
        }
        return view('agent.banlist', [
            'agents' => $users,
        ]);
    }

    public function agentInfo()
    {
        $user = User::find($this->params['id']);
        if (empty($user)) {
            throw new SlException(SlException::AGENT_NOT_EXSIST_CODE);
        }
        return view('agent.info', [
            'agent_info' => $user
        ]);
    }

    public function rechargeList(Request $request)
    {
        // 参数校验
        $this->validate($request, [
            'id' => 'required|integer',
            'start_date' => 'date_format:Y-m-d|nullable',
            'end_date' => 'date_format:Y-m-d|nullable'
        ]);
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        $start_time = isset($this->params['start_date']) ? $this->params['start_date'] : Carbon::today()->toDateString();
        $end_time = isset($this->params['end_date']) ? $this->params['end_date'] : Carbon::tomorrow()->toDateString();

        $recharge_list = TransactionFlow::where('recipient_id', $this->params['id'])
            ->whereBetween('created_at', [$start_time, $end_time])
            ->paginate($page_size);

        return view('agent.rechargelist', [
            'recharge_list' => $recharge_list
        ]);
    }

    public function showOpenRoomForm(Request $request)
    {
        $cities = [];
        $user = Auth::user();
        if (!$user->hasRole('agent')) {
            $cities = (new UserLogic())->getAllOpenCities();
        }
        return view('agent.openroom', [
            'agent' => $user,
            'cities' => $cities,
        ]);
    }

    public function openRoom(Request $request)
    {
        $this->validate($request, [
            'server_id' => 'required|integer'
        ]);

        $login_user = Auth::user();

        $is_recharged = true;

        DB::beginTransaction();

        try {
            if ($login_user->hasRole(Constants::ROLE_AGENT)) {
                $account = Accounts::where([
                    'user_id' => $login_user->id,
                    'type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                ])->first();
                if (empty($account) || $account->balance < Constants::OPEN_ROOM_CARD_REDUCE) {
                    throw new SlException(SlException::ACCOUNT_BALANCE_NOT_ENOUGH);
                }
                $account->balance -= Constants::OPEN_ROOM_CARD_REDUCE;
                $account->save();
            }

            // 调用idip注册服务器
            $inner_meta_register_srv = Protobuf::packRegisterInnerMeta();
            $register_res = TcpClient::callTcpService($inner_meta_register_srv, true);
            if ($register_res !== $inner_meta_register_srv) {
                throw new SlException(SlException::GMT_SERVER_REGISTER_FAIL_CODE);
            }
            // 调用idip代开房
            $open_room['server_id'] = $this->params['server_id'];
            $inner_meta_open_room = Protobuf::packOpenRoomInnerMeta($open_room);
            $open_room_res = Protobuf::unpackOpenRoom(TcpClient::callTcpService($inner_meta_open_room));
            if ($open_room_res['error_code'] != 0) {
                throw new SlException(SlException::GMT_SERVER_OPEN_ROOM_FAIL_CODE);
            }
            DB::commit();
        } catch (\Exception $e) {
            $is_recharged = false;
            $error_code = $e->getCode();
            $error_message = $e->getMessage();
            // 关闭socket连接
            if (TcpClient::isAlive()) {
                TcpClient::getSocket()->close();
            }
            DB::rollback();
            if ($e->getCode() == SlException::GMT_SERVER_OPEN_ROOM_FAIL_CODE) {
                $recharge_fail_reason = json_encode($open_room_res);
            } else {
                $recharge_fail_reason = json_encode([
                    'error_code' => $e->getCode(),
                    'error_msg' => $e->getMessage(),
                ]);
            }
        }

        $transaction_flow = new TransactionFlow();
        $transaction_flow->initiator_id = Auth::id();
        $transaction_flow->initiator_name = Auth::user()->name;
        $transaction_flow->initiator_type = Constants::$recharge_role_type[$login_user->roles()->first()->toArray()['name']];
        $transaction_flow->recharge_type = Constants::OPEN_ROOM_TYPE;
        $transaction_flow->num = Constants::OPEN_ROOM_CARD_REDUCE;

        if ($is_recharged) {
            $transaction_flow->status = Constants::COMMON_ENABLE;
            $transaction_flow->result = json_encode($open_room_res);
        } else {
            $transaction_flow->recharge_fail_reason = $recharge_fail_reason;
        }
        $transaction_flow->save();

        if (!$is_recharged) {
            throw new SlException($error_code, $error_message);
        }

        return view('agent.openroomres', [
            'room_id' => $open_room_res['room_id']
        ]);
    }

}