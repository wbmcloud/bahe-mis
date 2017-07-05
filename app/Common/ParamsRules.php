<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/18
 * Time: 上午8:53
 */

namespace App\Common;

class ParamsRules
{

    const IF_DASHBOARD = '/dashboard';

    /**
     * 登录模块
     */
    const IF_USER_LOGIN    = '/login';
    const IF_USER_DO_LOGIN = '/dologin';
    const IF_USER_LOGOUT   = '/logout';

    /**
     * 用户模块
     */
    const IF_USER_ADD      = '/user/add';
    const IF_USER_DO_ADD   = '/user/doadd';
    const IF_USER_RESET    = '/user/reset';
    const IF_USER_DO_RESET = '/user/doreset';

    const IF_USER_AGREE     = '/user/agree';
    const IF_USER_AGREEMENT = '/user/agreement';

    /**
     * 代理模块
     */
    const IF_AGENT_LIST          = '/agent/list';
    const IF_AGENT_BAN_LIST      = '/agent/banlist';
    const IF_AGENT_INFO          = '/agent/info';
    const IF_AGENT_RECHARGE_LIST = '/agent/rechargelist';
    const IF_AGENT_OPEN_ROOM     = '/agent/openroom';
    const IF_AGENT_DO_OPEN_ROOM  = '/agent/doopenroom';

    const IF_API_AGENT_ADD   = '/api/agent/add';
    const IF_API_AGENT_BAN   = '/api/agent/ban';
    const IF_API_AGENT_UNBAN = '/api/agent/unban';
    const IF_API_AGENT_INFO  = '/api/agent/info';
    const IF_API_AGENT_SAVE  = '/api/agent/save';
    const IF_API_AGENT_RESET = '/api/agent/reset';

    /**
     * 一级代理模块
     */
    const IF_GENERAL_AGENT_LIST            = '/general_agent/list';
    const IF_GENERAL_AGENT_BAN_LIST        = '/general_agent/banlist';
    const IF_GENERAL_AGENT_ADD             = '/general_agent/add';
    const IF_GENERAL_AGENT_DO_ADD          = '/general_agent/doadd';
    const IF_GENERAL_AGENT_INVITE_CODE     = '/general_agent/invite_code';
    const IF_GENERAL_AGENT_RECHARGE_LIST   = '/general_agent/rechargelist';
    const IF_GENERAL_AGENT_CASH_ORDER_LIST = '/general_agent/cash_order_list';

    const IF_API_GENERAL_AGENT_INFO     = '/api/general_agent/info';
    const IF_API_GENERAL_AGENT_SAVE     = '/api/general_agent/save';
    const IF_API_GENERAL_AGENT_BAN      = '/api/general_agent/ban';
    const IF_API_GENERAL_AGENT_UNBAN    = '/api/general_agent/unban';
    const IF_API_GENERAL_AGENT_DEL_FLOW = '/api/general_agent/delflow';

    /**
     * 充值中心
     */
    const IF_RECHARGE_USER     = '/recharge/user';
    const IF_RECHARGE_DO_USER  = '/recharge/douser';
    const IF_RECHARGE_AGENT    = '/recharge/agent';
    const IF_RECHARGE_DO_AGENT = '/recharge/doagent';


    /**
     * @var array
     * 参数校验规则
     */
    public static $rules = [
        self::IF_API_AGENT_ADD               => [
            'id' => 'required|integer',
        ],
        self::IF_API_AGENT_RESET             => [
            'id' => 'required|integer',
        ],
        self::IF_API_AGENT_BAN               => [
            'id' => 'required|integer',
        ],
        self::IF_API_AGENT_UNBAN             => [
            'id' => 'required|integer',
        ],
        self::IF_API_AGENT_INFO              => [
            'id' => 'required|integer',
        ],
        self::IF_API_AGENT_SAVE              => [
            'id'          => 'required|integer',
            'city_id'     => 'required|integer',
            'invite_code' => 'integer|nullable',
            'uin'         => 'integer|nullable',
            'wechat'      => 'string|nullable',
            'uin_group'   => 'string|nullable',
            'tel'         => 'integer|nullable',
            'bank_card'   => 'string|nullable',
            'id_card'     => 'string|nullable',
        ],
        self::IF_USER_DO_ADD                 => [
            'type'        => ['required', 'in:1,2,3'],
            'user_name'   => 'required|string',
            'password'    => 'required|string',
            'city_id'     => 'integer|nullable',
            'invite_code' => 'integer|nullable',
            'name'        => 'string|nullable',
            'uin'         => 'integer|nullable',
            'wechat'      => 'string|nullable',
            'uin_group'   => 'string|nullable',
            'bank_card'   => 'integer|nullable',
            'tel'         => 'integer|nullable',
            'id_card'     => 'integer|nullable',
        ],
        self::IF_USER_DO_RESET               => [
            'old_password' => 'required|string',
            'new_password' => 'required|string',
            'dup_password' => 'required|string',
        ],
        self::IF_AGENT_LIST                  => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_AGENT_BAN_LIST              => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_AGENT_INFO                  => [
            'id' => 'required|integer',
        ],
        self::IF_AGENT_RECHARGE_LIST         => [
            'id'         => 'required|integer',
            'start_date' => 'date_format:Y-m-d|nullable',
            'end_date'   => 'date_format:Y-m-d|nullable',
            'page'       => 'integer|nullable',
            'page_size'  => 'integer|nullable'
        ],
        self::IF_AGENT_DO_OPEN_ROOM          => [
            'server_id' => 'required|integer',
        ],
        self::IF_GENERAL_AGENT_LIST          => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_GENERAL_AGENT_INVITE_CODE   => [
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_GENERAL_AGENT_BAN_LIST      => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_GENERAL_AGENT_RECHARGE_LIST => [
            'invite_code' => 'required|digits:7',
            'start_date'  => 'date_format:Y-m-d',
            'end_date'    => 'date_format:Y-m-d',
            'page'        => 'integer|nullable',
            'page_size'   => 'integer|nullable'
        ],
        self::IF_GENERAL_AGENT_DO_ADD        => [
            'name'        => 'required|string',
            'invite_code' => 'required|digits:7',
            'tel'         => 'integer|required',
            'bank_card'   => 'integer|nullable',
            'id_card'     => 'integer|nullable',
        ],
        self::IF_API_GENERAL_AGENT_BAN       => [
            'id' => 'required|integer',
        ],
        self::IF_API_GENERAL_AGENT_UNBAN     => [
            'id' => 'required|integer',
        ],
        self::IF_API_GENERAL_AGENT_INFO      => [
            'id' => 'required|integer',
        ],
        self::IF_API_GENERAL_AGENT_DEL_FLOW  => [
            'id' => 'required|integer',
        ],
        self::IF_API_GENERAL_AGENT_SAVE      => [
            'id'        => 'required|integer',
            'tel'       => 'integer|nullable',
            'bank_card' => 'string|nullable',
            'id_card'   => 'string|nullable',
        ],
        self::IF_RECHARGE_DO_USER            => [
            'role_id'       => 'integer|required',
            'num'           => 'integer|required',
            'recharge_type' => ['required', 'in:1,2,3'],
        ],
        self::IF_RECHARGE_DO_AGENT           => [
            'user_name'     => 'string|required',
            'num'           => 'integer|required',
            'recharge_type' => ['required', 'in:1,2,3'],
        ],
        self::IF_USER_DO_LOGIN               => [
            'name'     => 'required|string',
            'password' => 'required|string',
        ],
        self::IF_USER_AGREE                  => [
            'is_accept' => 'required|accepted',
        ],
        self::IF_USER_ADD => [
            'type' => ['required', 'in:1,2,3']
        ],
    ];

    /**
     * @var array
     * 接口渲染模板路径
     */
    public static $interface_tpl = [
        self::IF_AGENT_LIST                  => 'agent.list',
        self::IF_AGENT_BAN_LIST              => 'agent.banlist',
        self::IF_AGENT_INFO                  => 'agent.info',
        self::IF_AGENT_RECHARGE_LIST         => 'agent.rechargelist',
        self::IF_AGENT_OPEN_ROOM             => 'agent.openroom',
        self::IF_AGENT_DO_OPEN_ROOM          => 'agent.openroomres',
        self::IF_GENERAL_AGENT_LIST          => 'general_agent.list',
        self::IF_GENERAL_AGENT_INVITE_CODE   => 'general_agent.invite_code',
        self::IF_GENERAL_AGENT_BAN_LIST      => 'general_agent.banlist',
        self::IF_GENERAL_AGENT_RECHARGE_LIST => 'general_agent.recharge',
        self::IF_GENERAL_AGENT_ADD           => 'general_agent.add',
        self::IF_GENERAL_AGENT_DO_ADD        => 'success',
        self::IF_RECHARGE_USER               => 'recharge.user',
        self::IF_RECHARGE_AGENT              => 'recharge.agent',
        self::IF_RECHARGE_DO_USER            => 'success',
        self::IF_RECHARGE_DO_AGENT           => 'success',
        self::IF_USER_DO_ADD                 => 'success',
        self::IF_USER_RESET                  => 'auth.reset',
        self::IF_USER_DO_RESET               => 'success',
        self::IF_USER_LOGIN                  => 'auth.login',
        self::IF_USER_AGREE                  => 'dashboard',
        self::IF_GENERAL_AGENT_CASH_ORDER_LIST => 'general_agent.cash_order_list',
    ];
}