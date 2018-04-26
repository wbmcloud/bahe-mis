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
     * 总代理模块
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
    const IF_API_FIRST_AGENT_RESET         = '/api/first_agent/reset';

    /**
     * 总监模块
     */
    const IF_GENERAL_AGENT_LIST                      = '/general_agent/list';
    const IF_GENERAL_AGENT_BAN_LIST                  = '/general_agent/banlist';
    const IF_GENERAL_AGENT_INVITE_CODE               = '/general_agent/invite_code';
    const IF_GENERAL_AGENT_RECHARGE_LIST             = '/general_agent/rechargelist';
    const IF_GENERAL_AGENT_FIRST_AGENT_RECHARGE_LIST = '/general_agent/first_agent_rechargelist';
    const IF_GENERAL_AGENT_CASH_ORDER_LIST           = '/general_agent/cash_order_list';
    const IF_GENERAL_AGENT_INCOME                    = '/general_agent/income';
    const IF_GENERAL_AGENT_SALE                      = '/general_agent/sale';
    const IF_GENERAL_AGENT_INCOME_HISTORY            = '/general_agent/income_history';

    const IF_API_GENERAL_AGENT_INFO          = '/api/general_agent/info';
    const IF_API_GENERAL_AGENT_SAVE          = '/api/general_agent/save';
    const IF_API_GENERAL_AGENT_BAN           = '/api/general_agent/ban';
    const IF_API_GENERAL_AGENT_UNBAN         = '/api/general_agent/unban';
    const IF_API_GENERAL_AGENT_DEL_FLOW      = '/api/general_agent/delflow';
    const IF_API_GENERAL_AGENT_DO_CASH_ORDER = '/api/general_agent/do_cash_order';
    const IF_API_GENERAL_AGENT_RESET         = '/api/general_agent/reset';

    /**
     * 充值中心
     */
    const IF_RECHARGE_USER     = '/recharge/user';
    const IF_RECHARGE_DO_USER  = '/recharge/douser';
    const IF_RECHARGE_AGENT    = '/recharge/agent';
    const IF_RECHARGE_DO_AGENT = '/recharge/doagent';

    /**
     * 游戏模块
     */
    const IF_GAME_PLAYER_LIST       = '/game/playerlist';
    const IF_GAME_BIND_PLAYER       = '/game/bindplayer';
    const IF_GAME_DO_BIND_PLAYER    = '/game/dobindplayer';

    /**
     * 记录模块
     */
    const IF_RECORD_AGENT_RECHARGE = '/record/agentrecharge';
    const IF_RECORD_USER_RECHARGE  = '/record/userrecharge';
    const IF_RECORD_OPEN_ROOM      = '/record/openroom';
    const IF_RECORD_BIND_PLAYER    = '/record/bindplayer';

    /**
     * 统计模块
     */
    const IF_API_STAT_AGENT      = '/api/stat/agent';
    const IF_API_STAT_FLOW       = '/api/stat/flow';
    const IF_API_STAT_AGENT_FLOW = '/api/stat/agent_flow';
    const IF_API_STAT_ROUNDS     = '/api/stat/rounds';
    const IF_API_STAT_DAU        = '/api/stat/dau';
    const IF_API_STAT_WAU        = '/api/stat/wau';
    const IF_API_STAT_MAU        = '/api/stat/mau';
    const IF_STAT_AGENT          = '/stat/agent';
    const IF_STAT_FLOW           = '/stat/flow';
    const IF_STAT_AGENT_FLOW     = '/stat/agent_flow';
    const IF_STAT_ROUNDS         = '/stat/rounds';
    const IF_STAT_DAU            = '/stat/dau';
    const IF_STAT_WAU            = '/stat/wau';
    const IF_STAT_MAU            = '/stat/mau';

    /**
     * 基础模块
     */
    const IF_API_BASIC_CITY_CONFIG = '/api/basic/cityconfig';

    /**
     * @var array
     * 参数校验规则
     */
    public static $rules = [
        self::IF_API_AGENT_RESET                 => [
            'id'       => 'required|integer',
            'password' => 'required|string',
        ],
        self::IF_API_AGENT_BAN                   => [
            'id' => 'required|integer',
        ],
        self::IF_API_AGENT_UNBAN                 => [
            'id' => 'required|integer',
        ],
        self::IF_API_AGENT_INFO                  => [
            'id' => 'required|integer',
        ],
        self::IF_API_AGENT_SAVE                  => [
            'id'          => 'required|integer',
            'city_id'     => 'required|integer',
            'invite_code' => 'numeric|nullable',
            'uin'         => 'integer|nullable',
            'wechat'      => 'string|max:50|nullable',
            'uin_group'   => 'string|max:512|nullable',
            'tel'         => 'digits:11|nullable',
            'bank_card'   => 'string|max:50|nullable',
            'id_card'     => 'string|max:50|nullable',
        ],
        self::IF_USER_DO_ADD                     => [
            'type'        => ['required', 'in:1,2,3,4'],
            'user_name'   => 'required|string|between:6,22',
            'password'    => 'required|string',
            'city_id'     => 'integer|nullable',
            'code'        => 'digits:7|nullable',
            'invite_code' => 'numeric|nullable',
            'name'        => 'string|between:2,22|nullable',
            'uin'         => 'integer|nullable',
            'wechat'      => 'string|max:50|nullable',
            'uin_group'   => 'string|max:512|nullable',
            'bank_card'   => 'string|max:50|nullable',
            'tel'         => 'digits:11|nullable',
            'id_card'     => 'string|max:50|nullable',
        ],
        self::IF_USER_DO_RESET                   => [
            'old_password' => 'required|string',
            'new_password' => 'required|string',
            'dup_password' => 'required|string',
        ],
        self::IF_AGENT_LIST                      => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_AGENT_BAN_LIST                  => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_AGENT_INFO                      => [
            'id' => 'required|integer',
        ],
        self::IF_AGENT_RECHARGE_LIST             => [
            'id'         => 'required|integer',
            'start_date' => 'date_format:Y-m-d|nullable',
            'end_date'   => 'date_format:Y-m-d|nullable',
            'page'       => 'integer|nullable',
            'page_size'  => 'integer|nullable'
        ],
        self::IF_AGENT_DO_OPEN_ROOM              => [
            'server' => 'required|string',
            //'model' => ['required', 'in:1,2'],
            'extend_type' => 'array',
            'open_rands' => ['required', 'in:8,16,24'],
            'top_mutiple' => ['required', 'in:0,32,64,256'],
            'voice_open' => 'integer|nullable',
        ],
        self::IF_FIRST_AGENT_LIST                => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_FIRST_AGENT_INVITE_CODE         => [
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_FIRST_AGENT_BAN_LIST            => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_FIRST_AGENT_RECHARGE_LIST       => [
            'invite_code' => 'required|numeric',
            'start_date'  => 'date_format:Y-m-d',
            'end_date'    => 'date_format:Y-m-d',
            'page'        => 'integer|nullable',
            'page_size'   => 'integer|nullable'
        ],
        self::IF_API_FIRST_AGENT_BAN             => [
            'id' => 'required|integer',
        ],
        self::IF_API_FIRST_AGENT_UNBAN           => [
            'id' => 'required|integer',
        ],
        self::IF_API_FIRST_AGENT_INFO            => [
            'id' => 'required|integer',
        ],
        self::IF_API_FIRST_AGENT_DEL_FLOW        => [
            'id' => 'required|integer',
        ],
        self::IF_API_FIRST_AGENT_RESET           => [
            'id'       => 'required|integer',
            'password' => 'required|string',
        ],
        self::IF_API_FIRST_AGENT_SAVE            => [
            'id'        => 'required|integer',
            'tel'       => 'digits:11|nullable',
            'bank_card' => 'string|max:50|nullable',
            'id_card'   => 'string|max:50|nullable',
        ],
        self::IF_API_FIRST_AGENT_DO_CASH_ORDER   => [
            'id' => 'required|integer',
        ],
        self::IF_RECHARGE_DO_USER                => [
            'role_id'       => 'integer|required',
            'num'           => 'integer|required',
            'recharge_type' => ['required', 'in:1,2,3'],
        ],
        self::IF_RECHARGE_DO_AGENT               => [
            'user_name'     => 'required|string|between:6,22',
            'num'           => 'integer|required',
            'give_num'      => 'integer|nullable',
            'recharge_type' => ['required', 'in:1,2,3'],
        ],
        self::IF_USER_DO_LOGIN                   => [
            'user_name' => 'required|string|between:6,22',
            'password'  => 'required|string',
        ],
        self::IF_USER_AGREE                      => [
            'is_accept' => 'required|accepted',
        ],
        self::IF_USER_ADD                        => [
            'type' => ['required', 'in:1,2,3,4']
        ],
        self::IF_GENERAL_AGENT_LIST              => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_GENERAL_AGENT_INVITE_CODE       => [
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_GENERAL_AGENT_BAN_LIST          => [
            'query_str' => 'string|nullable',
            'page'      => 'integer|nullable',
            'page_size' => 'integer|nullable'
        ],
        self::IF_GENERAL_AGENT_RECHARGE_LIST     => [
            'invite_code' => 'required|numeric',
            'start_date'  => 'date_format:Y-m-d',
            'end_date'    => 'date_format:Y-m-d',
            'page'        => 'integer|nullable',
            'page_size'   => 'integer|nullable'
        ],
        self::IF_GENERAL_AGENT_SALE              => [
            'type' => ['required', 'in:3,4']
        ],
        self::IF_API_GENERAL_AGENT_BAN           => [
            'id' => 'required|integer',
        ],
        self::IF_API_GENERAL_AGENT_UNBAN         => [
            'id' => 'required|integer',
        ],
        self::IF_API_GENERAL_AGENT_INFO          => [
            'id' => 'required|integer',
        ],
        self::IF_API_GENERAL_AGENT_DEL_FLOW      => [
            'id' => 'required|integer',
        ],
        self::IF_API_GENERAL_AGENT_RESET         => [
            'id'       => 'required|integer',
            'password' => 'required|string',
        ],
        self::IF_API_GENERAL_AGENT_SAVE          => [
            'id'        => 'required|integer',
            'tel'       => 'digits:11|nullable',
            'bank_card' => 'string|max:50|nullable',
            'id_card'   => 'string|max:50|nullable',
        ],
        self::IF_API_GENERAL_AGENT_DO_CASH_ORDER => [
            'id' => 'required|integer',
        ],
        self::IF_API_BASIC_CITY_CONFIG => [
            'city_id' => 'required|integer',
        ],
        self::IF_GAME_DO_BIND_PLAYER => [
            'city' => 'required|integer',
            'player_id' => 'required|integer',
        ],
    ];

    /**
     * 接口权限
     * @var array
     */
    public static $interface_permission = [
        self::IF_USER_LOGIN                              => ['auth' => '*', 'desc' => '登录表单页面'],
        self::IF_USER_DO_LOGIN                           => ['auth' => '*', 'desc' => '登录动作'],
        self::IF_USER_LOGOUT                             => ['auth' => '*', 'desc' => '注销动作'],
        self::IF_DASHBOARD                               => ['auth' => '*', 'desc' => '仪表盘页面'],
        self::IF_PROMPT                                  => ['auth' => '*', 'desc' => '提示页面'],
        self::IF_NOT_FOUND                               => ['auth' => '*', 'desc' => '404页面'],
        self::IF_FATAL_ERROR                             => ['auth' => '*', 'desc' => '500错误页面'],
        self::IF_USER_ADD                                => ['auth' => ['super', 'admin'], 'desc' => '添加用户页面'],
        self::IF_USER_DO_ADD                             => ['auth' => ['super', 'admin'], 'desc' => '添加用户动作'],
        self::IF_USER_RESET                              => ['auth' => '*', 'desc' => '修改密码页面'],
        self::IF_USER_DO_RESET                           => ['auth' => '*', 'desc' => '修改密码动作'],
        self::IF_USER_AGREE                              => ['auth' => ['agent', 'first_agent', 'general_agent'], 'desc' => '代理协议同意动作'],
        self::IF_USER_AGREEMENT                          => ['auth' => ['agent', 'first_agent', 'general_agent'], 'desc' => '代理协议页面'],
        self::IF_AGENT_LIST                              => ['auth' => ['super', 'admin'], 'desc' => '代理列表页面'],
        self::IF_AGENT_BAN_LIST                          => ['auth' => ['super', 'admin'], 'desc' => '封禁代理页面页面'],
        self::IF_AGENT_INFO                              => ['auth' => ['super', 'admin'], 'desc' => '代理详情页面'],
        self::IF_AGENT_RECHARGE_LIST                     => ['auth' => '*', 'desc' => '代理充值记录页面'],
        self::IF_AGENT_OPEN_ROOM                         => ['auth' => '*', 'desc' => '代开房页面'],
        self::IF_AGENT_DO_OPEN_ROOM                      => ['auth' => '*', 'desc' => '代开房动作'],
        self::IF_API_AGENT_BAN                           => ['auth' => ['super', 'admin'], 'desc' => '封禁代理接口'],
        self::IF_API_AGENT_INFO                          => ['auth' => ['super', 'admin'], 'desc' => '获取代理详情信息接口'],
        self::IF_API_AGENT_UNBAN                         => ['auth' => ['super', 'admin'], 'desc' => '解封代理接口'],
        self::IF_API_AGENT_RESET                         => ['auth' => ['super', 'admin'], 'desc' => '重置代理接口'],
        self::IF_API_AGENT_SAVE                          => ['auth' => ['super', 'admin'], 'desc' => '保存代理接口'],
        self::IF_FIRST_AGENT_LIST                        => ['auth' => ['super', 'admin'], 'desc' => '总代理列表页面'],
        self::IF_FIRST_AGENT_BAN_LIST                    => ['auth' => ['super', 'admin'], 'desc' => '封禁总代理列表页面'],
        self::IF_FIRST_AGENT_INVITE_CODE                 => ['auth' => ['super', 'admin'], 'desc' => '邀请码列表页面'],
        self::IF_FIRST_AGENT_RECHARGE_LIST               => ['auth' => ['super', 'admin', 'first_agent'], 'desc' => '总代理充值记录页面'],
        self::IF_FIRST_AGENT_CASH_ORDER_LIST             => ['auth' => ['super', 'admin'], 'desc' => '总代理每周打款单'],
        self::IF_FIRST_AGENT_INCOME                      => ['auth' => ['super', 'admin', 'first_agent'], 'desc' => '总代理收入统计'],
        self::IF_FIRST_AGENT_SALE                        => ['auth' => ['super', 'admin', 'first_agent'], 'desc' => '总代理销售明细'],
        self::IF_FIRST_AGENT_INCOME_HISTORY              => ['auth' => ['super', 'admin', 'first_agent'], 'desc' => '总代理收入历史'],
        self::IF_API_FIRST_AGENT_BAN                     => ['auth' => ['super', 'admin'], 'desc' => '总代理封禁接口'],
        self::IF_API_FIRST_AGENT_UNBAN                   => ['auth' => ['super', 'admin'], 'desc' => '总代理解封接口'],
        self::IF_API_FIRST_AGENT_INFO                    => ['auth' => ['super', 'admin'], 'desc' => '总代理信息接口'],
        self::IF_API_FIRST_AGENT_SAVE                    => ['auth' => ['super', 'admin'], 'desc' => '总代理信息保存接口'],
        self::IF_API_FIRST_AGENT_DEL_FLOW                => ['auth' => ['super', 'admin'], 'desc' => '删除代理充值记录接口'],
        self::IF_API_FIRST_AGENT_DO_CASH_ORDER           => ['auth' => ['super', 'admin'], 'desc' => '总代理打款单确认接口'],
        self::IF_API_FIRST_AGENT_RESET                   => ['auth' => ['super', 'admin'], 'desc' => '总代理密码重置接口'],
        self::IF_RECHARGE_AGENT                          => ['auth' => ['super', 'admin'], 'desc' => '代理充值页面'],
        self::IF_RECHARGE_DO_AGENT                       => ['auth' => ['super', 'admin'], 'desc' => '代理充值动作'],
        self::IF_RECHARGE_USER                           => ['auth' => '*', 'desc' => '用户充值页面'],
        self::IF_RECHARGE_DO_USER                        => ['auth' => '*', 'desc' => '用户充值动作'],
        self::IF_GENERAL_AGENT_LIST                      => ['auth' => ['super', 'admin'], 'desc' => '总监列表页面'],
        self::IF_GENERAL_AGENT_BAN_LIST                  => ['auth' => ['super', 'admin'], 'desc' => '封禁总监列表页面'],
        self::IF_GENERAL_AGENT_INVITE_CODE               => ['auth' => ['super', 'admin'], 'desc' => '邀请码列表页面'],
        self::IF_GENERAL_AGENT_RECHARGE_LIST             => ['auth' => ['super', 'admin', 'general_agent'], 'desc' => '总监充值记录页面'],
        self::IF_GENERAL_AGENT_FIRST_AGENT_RECHARGE_LIST => ['auth' => ['super', 'admin', 'general_agent'], 'desc' => '总代理销售记录页面'],
        self::IF_GENERAL_AGENT_CASH_ORDER_LIST           => ['auth' => ['super', 'admin'], 'desc' => '总监每周打款单'],
        self::IF_GENERAL_AGENT_INCOME                    => ['auth' => ['super', 'admin','general_agent'], 'desc' => '总监收入统计'],
        self::IF_GENERAL_AGENT_SALE                      => ['auth' => ['super', 'admin','general_agent'], 'desc' => '总监销售明细'],
        self::IF_GENERAL_AGENT_INCOME_HISTORY            => ['auth' => ['super', 'admin','general_agent'], 'desc' => '总监收入历史'],
        self::IF_API_GENERAL_AGENT_BAN                   => ['auth' => ['super', 'admin'], 'desc' => '总监封禁接口'],
        self::IF_API_GENERAL_AGENT_UNBAN                 => ['auth' => ['super', 'admin'], 'desc' => '总监解封接口'],
        self::IF_API_GENERAL_AGENT_INFO                  => ['auth' => ['super', 'admin'], 'desc' => '总监信息接口'],
        self::IF_API_GENERAL_AGENT_SAVE                  => ['auth' => ['super', 'admin'], 'desc' => '总监信息保存接口'],
        self::IF_API_GENERAL_AGENT_DEL_FLOW              => ['auth' => ['super', 'admin'], 'desc' => '删除代理充值记录接口'],
        self::IF_API_GENERAL_AGENT_DO_CASH_ORDER         => ['auth' => ['super', 'admin'], 'desc' => '总监打款单确认接口'],
        self::IF_API_GENERAL_AGENT_RESET                 => ['auth' => ['super', 'admin'], 'desc' => '总监密码重置接口'],
        self::IF_GAME_PLAYER_LIST                        => ['auth' => ['super', 'admin'], 'desc' => '游戏角色列表查询接口'],
        self::IF_RECORD_AGENT_RECHARGE                   => ['auth' => ['super', 'admin'], 'desc' => '代理充值记录'],
        self::IF_RECORD_USER_RECHARGE                    => ['auth' => ['super', 'admin'], 'desc' => '用户充值记录'],
        self::IF_RECORD_OPEN_ROOM                        => ['auth' => '*', 'desc' => '代开房记录'],
        self::IF_API_STAT_AGENT                          => ['auth' => ['super', 'admin'], 'desc' => '代理统计'],
        self::IF_API_STAT_FLOW                           => ['auth' => ['super', 'admin'], 'desc' => '流水统计'],
        self::IF_API_STAT_AGENT_FLOW                     => ['auth' => ['super', 'admin'], 'desc' => '流水统计'],
        self::IF_API_STAT_ROUNDS                         => ['auth' => ['super', 'admin'], 'desc' => '局数统计'],
        self::IF_API_STAT_DAU                            => ['auth' => ['super', 'admin'], 'desc' => 'DAU统计'],
        self::IF_API_STAT_WAU                            => ['auth' => ['super', 'admin'], 'desc' => 'WAU统计'],
        self::IF_API_STAT_MAU                            => ['auth' => ['super', 'admin'], 'desc' => 'MAU统计'],
        self::IF_STAT_AGENT                              => ['auth' => ['super', 'admin'], 'desc' => '代理统计页面'],
        self::IF_STAT_FLOW                               => ['auth' => ['super', 'admin'], 'desc' => '流水统计页面'],
        self::IF_STAT_AGENT_FLOW                         => ['auth' => ['super', 'admin'], 'desc' => '流水统计页面'],
        self::IF_STAT_ROUNDS                             => ['auth' => ['super', 'admin'], 'desc' => '局数统计页面'],
        self::IF_STAT_DAU                                => ['auth' => ['super', 'admin'], 'desc' => 'DAU统计页面'],
        self::IF_STAT_WAU                                => ['auth' => ['super', 'admin'], 'desc' => 'WAU统计页面'],
        self::IF_STAT_MAU                                => ['auth' => ['super', 'admin'], 'desc' => 'MAU统计页面'],
        self::IF_API_BASIC_CITY_CONFIG                   => ['auth' => '*', 'desc' => '城市配置'],
        self::IF_GAME_BIND_PLAYER                        => ['auth' => '*', 'desc' => '绑定角色页面'],
        self::IF_GAME_DO_BIND_PLAYER                     => ['auth' => '*', 'desc' => '绑定角色'],
        self::IF_RECORD_BIND_PLAYER                      => ['auth' => '*', 'desc' => '绑定角色记录'],
    ];

    /**
     * @var array
     * 接口渲染模板路径
     */
    public static $interface_tpl = [
        self::IF_DASHBOARD                               => 'dashboard',
        self::IF_AGENT_LIST                              => 'agent.list',
        self::IF_AGENT_BAN_LIST                          => 'agent.banlist',
        self::IF_AGENT_INFO                              => 'agent.info',
        self::IF_AGENT_RECHARGE_LIST                     => 'agent.rechargelist',
        self::IF_AGENT_OPEN_ROOM                         => 'agent.openroom',
        self::IF_AGENT_DO_OPEN_ROOM                      => 'res',
        self::IF_FIRST_AGENT_LIST                        => 'first_agent.list',
        self::IF_FIRST_AGENT_INVITE_CODE                 => 'first_agent.invite_code',
        self::IF_FIRST_AGENT_BAN_LIST                    => 'first_agent.banlist',
        self::IF_FIRST_AGENT_RECHARGE_LIST               => 'first_agent.recharge',
        self::IF_RECHARGE_USER                           => 'recharge.user',
        self::IF_RECHARGE_AGENT                          => 'recharge.agent',
        self::IF_USER_RESET                              => 'auth.reset',
        self::IF_USER_LOGIN                              => 'auth.login',
        self::IF_FIRST_AGENT_CASH_ORDER_LIST             => 'first_agent.cash_order_list',
        self::IF_FIRST_AGENT_INCOME                      => 'first_agent.income',
        self::IF_FIRST_AGENT_SALE                        => 'first_agent.sale',
        self::IF_FIRST_AGENT_INCOME_HISTORY              => 'first_agent.income_history',
        self::IF_GENERAL_AGENT_LIST                      => 'general_agent.list',
        self::IF_GENERAL_AGENT_INVITE_CODE               => 'general_agent.invite_code',
        self::IF_GENERAL_AGENT_BAN_LIST                  => 'general_agent.banlist',
        self::IF_GENERAL_AGENT_RECHARGE_LIST             => 'general_agent.recharge',
        self::IF_GENERAL_AGENT_FIRST_AGENT_RECHARGE_LIST => 'general_agent.first_agent_recharge',
        self::IF_GENERAL_AGENT_CASH_ORDER_LIST           => 'general_agent.cash_order_list',
        self::IF_GENERAL_AGENT_INCOME                    => 'general_agent.income',
        self::IF_GENERAL_AGENT_SALE                      => 'general_agent.sale',
        self::IF_GENERAL_AGENT_INCOME_HISTORY            => 'general_agent.income_history',
        self::IF_GAME_PLAYER_LIST                        => 'game.player_list',
        self::IF_RECORD_AGENT_RECHARGE                   => 'record.agentrecharge',
        self::IF_RECORD_USER_RECHARGE                    => 'record.userrecharge',
        self::IF_RECORD_OPEN_ROOM                        => 'record.openroom',
        self::IF_RECORD_BIND_PLAYER                      => 'record.bindplayer',
        self::IF_STAT_AGENT                              => 'stat.stat_agent',
        self::IF_STAT_FLOW                               => 'stat.stat_flow',
        self::IF_STAT_AGENT_FLOW                         => 'stat.stat_agent_flow',
        self::IF_STAT_ROUNDS                             => 'stat.stat_rounds',
        self::IF_STAT_DAU                                => 'stat.stat_dau',
        self::IF_STAT_WAU                                => 'stat.stat_wau',
        self::IF_STAT_MAU                                => 'stat.stat_mau',
        self::IF_GAME_BIND_PLAYER                        => 'game.bindplayer',
    ];

    /**
     * @var array
     * 写入操作
     */
    public static $action_interface = [
        self::IF_USER_DO_ADD,
        self::IF_USER_DO_RESET,
        self::IF_RECHARGE_DO_AGENT,
        self::IF_RECHARGE_DO_USER,
        self::IF_AGENT_DO_OPEN_ROOM,
        self::IF_API_AGENT_BAN,
        self::IF_API_AGENT_UNBAN,
        self::IF_API_AGENT_RESET,
        self::IF_API_AGENT_SAVE,
        self::IF_API_FIRST_AGENT_BAN,
        self::IF_API_FIRST_AGENT_UNBAN,
        self::IF_API_FIRST_AGENT_SAVE,
        self::IF_API_FIRST_AGENT_DO_CASH_ORDER,
        self::IF_API_FIRST_AGENT_DEL_FLOW,
        self::IF_API_FIRST_AGENT_RESET,
        self::IF_API_GENERAL_AGENT_BAN,
        self::IF_API_GENERAL_AGENT_UNBAN,
        self::IF_API_GENERAL_AGENT_SAVE,
        self::IF_API_GENERAL_AGENT_DO_CASH_ORDER,
        self::IF_API_GENERAL_AGENT_DEL_FLOW,
        self::IF_API_GENERAL_AGENT_RESET,
    ];
}