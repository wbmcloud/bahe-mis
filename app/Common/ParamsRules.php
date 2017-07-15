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

    const IF_DASHBOARD   = '/dashboard';
    const IF_NOT_FOUND   = '/404';
    const IF_FATAL_ERROR = '/500';
    const IF_PROMPT      = '/prompt';

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

    const IF_API_AGENT_BAN   = '/api/agent/ban';
    const IF_API_AGENT_UNBAN = '/api/agent/unban';
    const IF_API_AGENT_INFO  = '/api/agent/info';
    const IF_API_AGENT_SAVE  = '/api/agent/save';
    const IF_API_AGENT_RESET = '/api/agent/reset';

    /**
     * 一级代理模块
     */
    const IF_FIRST_AGENT_LIST            = '/first_agent/list';
    const IF_FIRST_AGENT_BAN_LIST        = '/first_agent/banlist';
    const IF_FIRST_AGENT_INVITE_CODE     = '/first_agent/invite_code';
    const IF_FIRST_AGENT_RECHARGE_LIST   = '/first_agent/rechargelist';
    const IF_FIRST_AGENT_CASH_ORDER_LIST = '/first_agent/cash_order_list';
    const IF_FIRST_AGENT_INCOME          = '/first_agent/income';
    const IF_FIRST_AGENT_SALE            = '/first_agent/sale';
    const IF_FIRST_AGENT_INCOME_HISTORY  = '/first_agent/income_history';

    const IF_API_FIRST_AGENT_INFO          = '/api/first_agent/info';
    const IF_API_FIRST_AGENT_SAVE          = '/api/first_agent/save';
    const IF_API_FIRST_AGENT_BAN           = '/api/first_agent/ban';
    const IF_API_FIRST_AGENT_UNBAN         = '/api/first_agent/unban';
    const IF_API_FIRST_AGENT_DEL_FLOW      = '/api/first_agent/delflow';
    const IF_API_FIRST_AGENT_DO_CASH_ORDER = '/api/first_agent/do_cash_order';

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
            'invite_code' => 'digits:7|nullable',
            'uin'         => 'integer|nullable',
            'wechat'      => 'string|nullable',
            'uin_group'   => 'string|nullable',
            'tel'         => 'digits:11|nullable',
            'bank_card'   => 'string|nullable',
            'id_card'     => 'string|nullable',
        ],
        self::IF_USER_DO_ADD                 => [
            'type'        => ['required', 'in:1,2,3'],
            'user_name'   => 'required|string',
            'password'    => 'required|string',
            'city_id'     => 'integer|nullable',
            'invite_code' => 'digits:7|nullable',
            'name'        => 'string|nullable',
            'uin'         => 'integer|nullable',
            'wechat'      => 'string|nullable',
            'uin_group'   => 'string|nullable',
            'bank_card'   => 'string|nullable',
            'tel'         => 'digits:11|nullable',
            'id_card'     => 'string|nullable',
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
        self::IF_FIRST_AGENT_LIST          => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_FIRST_AGENT_INVITE_CODE   => [
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_FIRST_AGENT_BAN_LIST      => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_FIRST_AGENT_RECHARGE_LIST => [
            'invite_code' => 'required|digits:7',
            'start_date'  => 'date_format:Y-m-d',
            'end_date'    => 'date_format:Y-m-d',
            'page'        => 'integer|nullable',
            'page_size'   => 'integer|nullable'
        ],
        self::IF_API_FIRST_AGENT_BAN       => [
            'id' => 'required|integer',
        ],
        self::IF_API_FIRST_AGENT_UNBAN     => [
            'id' => 'required|integer',
        ],
        self::IF_API_FIRST_AGENT_INFO      => [
            'id' => 'required|integer',
        ],
        self::IF_API_FIRST_AGENT_DEL_FLOW  => [
            'id' => 'required|integer',
        ],
        self::IF_API_FIRST_AGENT_SAVE      => [
            'id'        => 'required|integer',
            'tel'       => 'digits:11|nullable',
            'bank_card' => 'string|nullable',
            'id_card'   => 'string|nullable',
        ],
        self::IF_API_FIRST_AGENT_DO_CASH_ORDER => [
            'id'        => 'required|integer',
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
     * 接口权限
     * @var array
     */
    public static $interface_permission = [
        self::IF_USER_LOGIN                      => ['auth' => '*', 'desc' => '登录表单页面'],
        self::IF_USER_DO_LOGIN                   => ['auth' => '*', 'desc' => '登录动作'],
        self::IF_USER_LOGOUT                     => ['auth' => '*', 'desc' => '注销动作'],
        self::IF_DASHBOARD                       => ['auth' => '*', 'desc' => '仪表盘页面'],
        self::IF_PROMPT                          => ['auth' => '*', 'desc' => '提示页面'],
        self::IF_NOT_FOUND                       => ['auth' => '*', 'desc' => '404页面'],
        self::IF_FATAL_ERROR                     => ['auth' => '*', 'desc' => '500错误页面'],
        self::IF_USER_ADD                        => ['auth' => ['super', 'admin'], 'desc' => '添加用户页面'],
        self::IF_USER_DO_ADD                     => ['auth' => ['super', 'admin'], 'desc' => '添加用户动作'],
        self::IF_USER_RESET                      => ['auth' => ['super', 'admin'], 'desc' => '修改密码页面'],
        self::IF_USER_DO_RESET                   => ['auth' => ['super', 'admin'], 'desc' => '修改密码动作'],
        self::IF_USER_AGREE                      => ['auth' => ['agent', 'first_agent'], 'desc' => '代理协议同意动作'],
        self::IF_USER_AGREEMENT                  => ['auth' => ['agent', 'first_agent'], 'desc' => '代理协议页面'],
        self::IF_AGENT_LIST                      => ['auth' => ['super', 'admin'], 'desc' => '代理列表页面'],
        self::IF_AGENT_BAN_LIST                  => ['auth' => ['super', 'admin'], 'desc' => '封禁代理页面页面'],
        self::IF_AGENT_INFO                      => ['auth' => ['super', 'admin'], 'desc' => '代理详情页面'],
        self::IF_AGENT_RECHARGE_LIST             => ['auth' => '*', 'desc' => '代理充值记录页面'],
        self::IF_AGENT_OPEN_ROOM                 => ['auth' => '*', 'desc' => '代开房页面'],
        self::IF_AGENT_DO_OPEN_ROOM              => ['auth' => '*', 'desc' => '代开房动作'],
        self::IF_API_AGENT_BAN                   => ['auth' => ['super', 'admin'], 'desc' => '封禁代理接口'],
        self::IF_API_AGENT_INFO                  => ['auth' => ['super', 'admin'], 'desc' => '获取代理详情信息接口'],
        self::IF_API_AGENT_UNBAN                 => ['auth' => ['super', 'admin'], 'desc' => '解封代理接口'],
        self::IF_API_AGENT_RESET                 => ['auth' => ['super', 'admin'], 'desc' => '重置代理接口'],
        self::IF_API_AGENT_SAVE                  => ['auth' => ['super', 'admin'], 'desc' => '保存代理接口'],
        self::IF_FIRST_AGENT_LIST              => ['auth' => ['super', 'admin'], 'desc' => '一级代理列表页面'],
        self::IF_FIRST_AGENT_BAN_LIST          => ['auth' => ['super', 'admin'], 'desc' => '封禁一级代理列表页面'],
        self::IF_FIRST_AGENT_INVITE_CODE       => ['auth' => ['super', 'admin'], 'desc' => '邀请码列表页面'],
        self::IF_FIRST_AGENT_RECHARGE_LIST     => ['auth' => ['super', 'admin', 'first_agent'], 'desc' => '一级代理充值记录页面'],
        self::IF_FIRST_AGENT_CASH_ORDER_LIST   => ['auth' => ['super', 'admin'], 'desc' => '一级代理每周打款单'],
        self::IF_FIRST_AGENT_INCOME            => ['auth' => ['first_agent'], 'desc' => '一级代理收入统计'],
        self::IF_FIRST_AGENT_SALE              => ['auth' => ['first_agent'], 'desc' => '以及代理销售明细'],
        self::IF_FIRST_AGENT_INCOME_HISTORY    => ['auth' => ['first_agent'], 'desc' => '一级代理收入历史'],
        self::IF_API_FIRST_AGENT_BAN           => ['auth' => ['super', 'admin'], 'desc' => '一级代理封禁接口'],
        self::IF_API_FIRST_AGENT_UNBAN         => ['auth' => ['super', 'admin'], 'desc' => '一级代理解封接口'],
        self::IF_API_FIRST_AGENT_INFO          => ['auth' => ['super', 'admin'], 'desc' => '一级代理信息接口'],
        self::IF_API_FIRST_AGENT_SAVE          => ['auth' => ['super', 'admin'], 'desc' => '一级代理信息保存接口'],
        self::IF_API_FIRST_AGENT_DEL_FLOW      => ['auth' => ['super', 'admin'], 'desc' => '删除代理充值记录接口'],
        self::IF_API_FIRST_AGENT_DO_CASH_ORDER => ['auth' => ['super', 'admin'], 'desc' => '一级代理打款单确认接口'],
        self::IF_RECHARGE_AGENT                  => ['auth' => ['super', 'admin'], 'desc' => '代理充值页面'],
        self::IF_RECHARGE_DO_AGENT               => ['auth' => ['super', 'admin'], 'desc' => '代理充值动作'],
        self::IF_RECHARGE_USER                   => ['auth' => '*', 'desc' => '用户充值页面'],
        self::IF_RECHARGE_DO_USER                => ['auth' => '*', 'desc' => '用户充值动作'],
    ];

    /**
     * @var array
     * 接口渲染模板路径
     */
    public static $interface_tpl = [
        self::IF_AGENT_LIST                    => 'agent.list',
        self::IF_AGENT_BAN_LIST                => 'agent.banlist',
        self::IF_AGENT_INFO                    => 'agent.info',
        self::IF_AGENT_RECHARGE_LIST           => 'agent.rechargelist',
        self::IF_AGENT_OPEN_ROOM               => 'agent.openroom',
        self::IF_AGENT_DO_OPEN_ROOM            => 'agent.openroomres',
        self::IF_FIRST_AGENT_LIST            => 'first_agent.list',
        self::IF_FIRST_AGENT_INVITE_CODE     => 'first_agent.invite_code',
        self::IF_FIRST_AGENT_BAN_LIST        => 'first_agent.banlist',
        self::IF_FIRST_AGENT_RECHARGE_LIST   => 'first_agent.recharge',
        self::IF_RECHARGE_USER                 => 'recharge.user',
        self::IF_RECHARGE_AGENT                => 'recharge.agent',
        self::IF_USER_RESET                    => 'auth.reset',
        self::IF_USER_LOGIN                    => 'auth.login',
        self::IF_FIRST_AGENT_CASH_ORDER_LIST => 'first_agent.cash_order_list',
        self::IF_FIRST_AGENT_INCOME          => 'first_agent.income',
        self::IF_FIRST_AGENT_SALE            => 'first_agent.sale',
        self::IF_FIRST_AGENT_INCOME_HISTORY  => 'first_agent.income_history',
    ];
}