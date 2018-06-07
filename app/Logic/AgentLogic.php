<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Common\Constants;
use App\Exceptions\BaheException;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Library\Protobuf\INNER_TYPE;
use App\Library\Protobuf\Protobuf;
use App\Library\TcpClient;
use App\Models\InviteCode;
use App\Models\TransactionFlow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentLogic extends BaseLogic
{
    /**
     * @param     $params
     * @param     $page_size
     * @param int $status
     * @return mixed
     */
    public function getAgentList($params, $page_size, $status = Constants::COMMON_ENABLE)
    {
        $condition = [
            'role_id' => Constants::ROLE_TYPE_AGENT,
            'status' => $status,
        ];

        $users = User::where($condition);

        if (!empty($params)) {
            if (isset($params['query_str']) && !empty($params['query_str'])) {
                $users = $users->where('user_name', 'like', "%{$params['query_str']}%");
            }

            if (empty($params['query_str']) && isset($params['start_date']) && !empty($params['start_date']) &&
                isset($params['end_date']) && !empty($params['end_date'])) {
                $users = $users->whereBetween('last_login_time', [$params['start_date'], $params['end_date']]);
            }
        }

        $users = $users->orderBy('id', 'desc')
            ->simplePaginate($page_size);

        return $users;
    }

    /**
     * @param $id
     * @return mixed
     * @throws BaheException
     */
    public function getAgentInfoById($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            throw new BaheException(BaheException::AGENT_NOT_EXIST_CODE);
        }
        return $user;
    }

    /**
     * @param $agent_id
     * @param $start_time
     * @param $end_time
     * @param $page_size
     * @return mixed
     */
    public function getAgentRechargeFlows($agent_id, $start_time, $end_time, $page_size)
    {
        return TransactionFlow::where([
                'recipient_id' => $agent_id,
            ])
            ->whereIn('recipient_type', Constants::$agent_role_type)
            ->whereBetween('created_at', [$start_time, $end_time])
            ->orderBy('id', 'desc')
            ->simplePaginate($page_size);
    }

    /**
     * @param $params
     * @param $open_room_res
     * @return array
     * @throws BaheException
     * @throws \Exception
     */
    public function sendGmtOpenRoom($params, &$open_room_res)
    {
        $server['ip'] = $params['gmt_server_ip'];
        $server['port'] = $params['gmt_server_port'];

        // 调用gmt注册服务器
        $inner_meta_register_srv = Protobuf::packRegisterInnerMeta();
        $register_res            = TcpClient::callTcpService($inner_meta_register_srv, true, $server);
        if (Protobuf::unpackRegister($register_res)->getTypeT() !== INNER_TYPE::INNER_TYPE_REGISTER) {
            throw new BaheException(BaheException::GMT_SERVER_REGISTER_FAIL_CODE);
        }

        // 调用gmt代开房
        $inner_meta_open_room   = Protobuf::packOpenRoomInnerMeta($params);
        $open_room_res          = Protobuf::unpackOpenRoom(TcpClient::callTcpService($inner_meta_open_room, false, $server));
        if ($open_room_res['error_code'] != 0) {
            throw new BaheException(BaheException::GMT_SERVER_OPEN_ROOM_FAIL_CODE);
        }

        return $open_room_res;
    }

    /**
     * @param      $params
     * @param      $user
     * @param bool $is_recharged
     * @param      $recharge_res
     * @param      $recharge_fail_reason
     * @param      $num
     * @return bool
     */
    public function saveOpenRoomTransactionFlow($params, $user, $is_recharged = false,
                                        $recharge_res, $recharge_fail_reason, $num)
    {
        $transaction_flow                 = new TransactionFlow();
        $transaction_flow->initiator_id   = $user->id;
        $transaction_flow->initiator_name = $user->user_name;
        $transaction_flow->initiator_type = Constants::$recharge_role_type[$user->roles()->first()->toArray()['name']];
        $transaction_flow->recipient_type = Constants::ROLE_TYPE_USER;
        $transaction_flow->recharge_type  = Constants::COMMAND_TYPE_OPEN_ROOM;
        $transaction_flow->num            = $num;
        $transaction_flow->req_params     = json_encode($params);

        $transaction_flow->game_server_id = $params['game_server_id'];
        isset($params['city_id']) && $transaction_flow->city_id = $params['city_id'];

        if ($is_recharged) {
            $transaction_flow->status = Constants::COMMON_ENABLE;
            $transaction_flow->result = json_encode($recharge_res);
        } else {
            $transaction_flow->recharge_fail_reason = $recharge_fail_reason;
        }
        $transaction_flow->save();

        return true;
    }

    /**
     * @param $user
     * @param $params
     * @return array
     * @throws BaheException
     */
    public function openRoom($user, $params)
    {
        $is_recharged = true;
        DB::beginTransaction();
        try {
            if ($params['game_type'] == Constants::GAME_TYPE_DDZ) {
                $num = $params['open_rands'] * Constants::DDZ_ROOM_CARD_RANDOM_FACTOR;
            } else {
                $num = ($params['open_rands'] / Constants::ROOM_CARD_RANDOMS) * Constants::ROOM_CARD_FISSION_FACTOR;
            }

            if (!$user->hasRole(Constants::$admin_role)) {
                $account_logic = new AccountLogic();
                $account_logic->reduceBalance($user->user_name,
                    COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD, $num);
            }

            $open_room_res = $this->sendGmtOpenRoom($params, $open_room_res);
            DB::commit();
        } catch (\Exception $e) {
            $is_recharged  = false;
            $error_code    = $e->getCode();
            $error_message = $e->getMessage();
            // 关闭socket连接
            if (TcpClient::isAlive()) {
                TcpClient::getSocket([
                    'ip' => $params['gmt_server_ip'],
                    'port' => $params['gmt_server_port']
                ])->close();
            }
            DB::rollback();
            if ($e->getCode() == BaheException::GMT_SERVER_OPEN_ROOM_FAIL_CODE) {
                $recharge_fail_reason = json_encode($open_room_res);
            } else {
                $recharge_fail_reason = json_encode([
                    'error_code' => $e->getCode(),
                    'error_msg'  => $e->getMessage(),
                ]);
            }
        }

        $open_room_res        = isset($open_room_res) && !empty($open_room_res) ?
            $open_room_res : null;
        $recharge_fail_reason = isset($recharge_fail_reason) && !empty($recharge_fail_reason) ?
            $recharge_fail_reason : null;

        $this->saveOpenRoomTransactionFlow($params, $user, $is_recharged, $open_room_res,
            $recharge_fail_reason, $num);

        if (!$is_recharged) {
            throw new BaheException($error_code, $error_message);
        }

        return $open_room_res;
    }

    public function getAgentConsumeFlows($agent_id, $start_time, $end_time, $page_size)
    {
        return TransactionFlow::where([
                'initiator_id' => $agent_id
            ])
            ->whereBetween('created_at', [$start_time, $end_time])
            ->orderBy('id', 'desc')
            ->simplePaginate($page_size);
    }

    /**
     * @param $city_id
     * @param $invite_code
     * @return mixed
     * @throws BaheException
     */
    public function getInviteCode($city_id, $invite_code)
    {
        $invite_code = InviteCode::where([
            'city_id' => $city_id,
            'invite_code' => $invite_code,
        ])->first();
        if (empty($invite_code)) {
            throw new BaheException(BaheException::INVITE_CODE_NOT_VALID_CODE);
        }

        if ($invite_code['is_used'] == Constants::COMMON_DISABLE) {
            throw new BaheException(BaheException::INVITE_CODE_NOT_USED_CODE);
        }

        return $invite_code;
    }


    /**
     * @param $params
     * @param $start_time
     * @param $end_time
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function openRoomRecord($params, $start_time, $end_time)
    {
        if (!Auth::user()->hasRole(Constants::$admin_role)) {
            return TransactionFlow::where([
                    'recharge_type' => Constants::COMMAND_TYPE_OPEN_ROOM,
                    'initiator_id'  => Auth::id()
                ])
                ->whereBetween('created_at', [$start_time, $end_time])
                ->orderBy('id', 'desc')
                ->simplePaginate($params['page_size']);
        }

        if (isset($params['query_str']) && !empty($params['query_str'])) {
            return TransactionFlow::where([
                    'initiator_name' => $params['query_str'],
                    'recharge_type'  => Constants::COMMAND_TYPE_OPEN_ROOM
                ])
                ->orderBy('id', 'desc')
                ->simplePaginate($params['page_size']);
        }

        return TransactionFlow::where('recharge_type', Constants::COMMAND_TYPE_OPEN_ROOM)
            ->whereBetween('created_at', [$start_time, $end_time])
            ->orderBy('id', 'desc')
            ->simplePaginate($params['page_size']);
    }


    /**
     * @param $params
     * @return string
     */
    public function renderOpenRoomParams($params)
    {
        if (empty($params)) {
            return '';
        }

        $render_arr = [];
        $params = !is_array($params) ? json_decode($params, true) : $params;

        //$render_arr[] = Constants::$open_room_mode[$params['model']];

        if (isset($params['extend_type'])) {
            $fanxing_arr = array_map(function ($r) {
                return Constants::$open_room_fanxing[$r];
            }, $params['extend_type']);

            $render_arr[] = implode(',', $fanxing_arr);
        }

        if (isset($params['zhuang_type'])) {
            $render_arr[] = Constants::$open_room_zhuang_type[$params['zhuang_type']];
        }

        $render_arr[] = Constants::$open_room_rounds[$params['open_rands']];

        if (isset($params['game_type']) &&
            in_array($params['game_type'], Constants::$division_city_game_type)) {
            $render_arr[] = Constants::$open_room_top_multiple[$params['top_mutiple']];
        } else {
            $render_arr[] = Constants::$open_room_ddz_top_multiple[$params['top_mutiple']];
        }

        if (isset($params['voice_open']) && $params['voice_open'] == 1) {
            $render_arr[] = Constants::$open_room_voice[$params['voice_open']];
        } else {
            $params['voice_open'] = 0;
            $render_arr[] = Constants::$open_room_voice[$params['voice_open']];
        }

        return implode(' ', $render_arr);
    }

    /**
     * @param $user_id
     * @param $game_server_id
     * @return array
     */
    public function getLastOpenRoomSettings($user_id, $game_server_id)
    {
        $open_room_last_set = TransactionFlow::where([
            'initiator_id' => $user_id,
            'game_server_id' => $game_server_id,
            'recharge_type' => Constants::COMMAND_TYPE_OPEN_ROOM
        ])->orderBy('id', 'DESC')->first();

        return !empty($open_room_last_set) ? json_decode($open_room_last_set->toArray()['req_params'], true) : [];
    }


    /**
     * @param $city_id
     * @param $game_type
     * @return array
     */
    public function getOpenRoomSetting($city_id, $game_type)
    {
        $user_id = Auth::id();
        $settings = [];

        $game_server = (new GameLogic())->getGameServerByCityIdAndType($city_id, $game_type);
        if (!empty($game_server)) {
            $settings = (new AgentLogic())->getLastOpenRoomSettings($user_id, $game_server['id']);
        }

        if (empty($settings)) {
            $settings = $this->fmtOpenRoomSetting(Constants::$open_room_default_params[$game_server['id']], $game_server);
        } else {
            if ($game_type == $settings['game_type']) {
                $settings = $this->fmtOpenRoomSetting($settings, $game_server);
            } else {
                $settings = $this->fmtOpenRoomSetting(Constants::$open_room_default_params[$game_server['id']], $game_server);
            }
        }

        return $settings;
    }

    /**
     * @param array $setting
     * @param       $game_server
     * @return array
     */
    public function fmtOpenRoomSetting(array $setting, $game_server)
    {
        $config = json_decode($game_server['config'], true);

        //番型
        if (isset($config['extend_type'])) {
            $setting['extend_type'] = $this->_fmtRenderParams($config['extend_type'], $setting['extend_type'],
                Constants::OPEN_ROOM_PARAM_TYPE_EXTEND_TYPE);
        }

        //局数
        if (isset($config['open_rands'])) {
            $setting['open_rands'] = $this->_fmtRenderParams($config['open_rands'], $setting['open_rands'],
                Constants::OPEN_ROOM_PARAM_TYPE_OPEN_RANDS);
        }

        //倍数
        if (isset($config['top_mutiple'])) {
            switch ($setting['game_type']) {
                case Constants::GAME_TYPE_MJ:
                    $setting['top_mutiple'] = $this->_fmtRenderParams($config['top_mutiple'], $setting['top_mutiple'],
                        Constants::OPEN_ROOM_PARAM_TYPE_TOP_MULTIPLE);
                    break;
                case Constants::GAME_TYPE_DDZ:
                    $setting['top_mutiple'] = $this->_fmtRenderParams($config['top_mutiple'], $setting['top_mutiple'],
                        Constants::OPEN_ROOM_PARAM_TYPE_DDZ_TOP_MULTIPLE);
                default:
                    break;
            }
        }

        //装类型
        if (isset($config['zhuang_type'])) {
            $setting['zhuang_type'] = $this->_fmtRenderParams($config['zhuang_type'], $setting['zhuang_type'],
                Constants::OPEN_ROOM_PARAM_TYPE_ZHUANG_TYPE);
        }

        return $setting;
    }

    /**
     * @param $init_params
     * @param $selected_params
     * @param $type
     * @return array
     */
    private function _fmtRenderParams($init_params, $selected_params, $type)
    {
        $params = [];

        foreach ($init_params as $v) {
            $t = [];
            if (!in_array($v, (array)$selected_params)) {
                $t['is_checked'] = false;
            }
            $t['id'] = $v;
            $t['desc'] = $this->_getParamDesc($type, $v);
            $params[] = $t;
        }

        return $params;
    }

    /**
     * @param $type
     * @param $idx
     * @return string
     */
    private function _getParamDesc($type, $idx)
    {
        return Constants::${Constants::$open_room_param_map[$type]}[$idx];
    }
}