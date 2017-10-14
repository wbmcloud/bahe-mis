<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 下午10:57
 */

namespace App\Common;

use App\Library\Protobuf\COMMAND_TYPE;

class Constants
{
    /**
     * 角色名称
     */
    const ROLE_SUPER       = 'super';
    const ROLE_ADMIN       = 'admin';
    const ROLE_AGENT       = 'agent';
    const ROLE_FIRST_AGENT = 'first_agent';
    const ROLE_GENERAL_AGENT = 'general_agent';

    /**
     * 角色类型id
     */
    const ROLE_TYPE_USER        = 0;
    const ROLE_TYPE_SUPER       = 1;
    const ROLE_TYPE_ADMIN       = 2;
    const ROLE_TYPE_AGENT       = 3;
    const ROLE_TYPE_FIRST_AGENT = 4;
    const ROLE_TYPE_GENERAL_AGENT = 5;

    /**
     * 充值类型
     */
    public static $recharge_type = [
        COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
        COMMAND_TYPE::COMMAND_TYPE_RECHARGE,
        COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU,
    ];

    /**
     * GMT操作类型
     * @var array
     */
    public static $transaction_type = [
        COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD => '房卡',
        COMMAND_TYPE::COMMAND_TYPE_RECHARGE  => '钻石',
        COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU => '欢乐豆',
        self::COMMAND_TYPE_OPEN_ROOM         => '代开房',
    ];

    /**
     * 角色名和角色类型对应关系
     * @var array
     */
    public static $recharge_role_type = [
        self::ROLE_SUPER => self::ROLE_TYPE_SUPER,
        self::ROLE_ADMIN => self::ROLE_TYPE_ADMIN,
        self::ROLE_AGENT => self::ROLE_TYPE_AGENT,
        self::ROLE_FIRST_AGENT => self::ROLE_TYPE_FIRST_AGENT,
        self::ROLE_GENERAL_AGENT => self::ROLE_TYPE_GENERAL_AGENT,
    ];

    /**
     * 角色描述
     * @var array
     */
    public static $role_type = [
        self::ROLE_TYPE_USER  => '用户',
        self::ROLE_TYPE_SUPER => '超级管理员',
        self::ROLE_TYPE_ADMIN => '管理员',
        self::ROLE_TYPE_AGENT => '代理',
        self::ROLE_TYPE_FIRST_AGENT => '总代理',
        self::ROLE_TYPE_GENERAL_AGENT => '总监',
    ];

    public static $recharge_status = [
        self::COMMON_DISABLE => '失败',
        self::COMMON_ENABLE  => '成功',
    ];

    const COMMON_ENABLE  = 1;
    const COMMON_DISABLE = 0;

    /**
     * 分页相关配置
     */
    const DEFAULT_PAGE      = 1;
    const DEFAULT_PAGE_SIZE = 10;

    /**
     * 邀请码配置
     */
    const FIRST_AGENT_INVITE_CODE_LENGTH = 7;
    const INVITE_CODE_LENGTH     = 4;
    const INVITE_CODE_LEVEL_LENGTH = 3;

    const BATCH_SIZE = 100;

    const ROOM_CARD_ITEM_ID = 13303809;

    const OPEN_ROOM_CARD_REDUCE = 1;

    public static $agreement_uri = [
        ParamsRules::IF_USER_AGREEMENT,
        ParamsRules::IF_USER_AGREE,
    ];

    const ADD_USER_TYPE_ADMIN       = 1;
    const ADD_USER_TYPE_AGENT       = 2;
    const ADD_USER_TYPE_FIRST_AGENT = 3;
    const ADD_USER_TYPE_GENERAL_AGENT = 4;

    public static $recharge_role = [
        self::ROLE_AGENT,
        self::ROLE_FIRST_AGENT,
        self::ROLE_GENERAL_AGENT,
    ];

    public static $agent_role_type = [
        self::ROLE_TYPE_AGENT,
        self::ROLE_TYPE_FIRST_AGENT,
        self::ROLE_TYPE_GENERAL_AGENT
    ];

    public static $admin_role = [
        self::ROLE_SUPER,
        self::ROLE_ADMIN
    ];

    public static $level_agent = [
        self::ROLE_FIRST_AGENT,
        self::ROLE_GENERAL_AGENT,
    ];

    /**
     * 代开房类型
     */
    const COMMAND_TYPE_OPEN_ROOM = 11;

    /**
     * 房卡价钱
     */
    const ROOM_CARD_PRICE = 1.2;

    /**
     * 代理级别类型
     */
    const AGENT_LEVEL_GENERAL  = 1;
    const AGENT_LEVEL_FIRST = 2;

    /**
     * 操作结果
     */
    const OPERATOR_PROMPT_NOT_PERMISSION_TEXT = '请勿非法访问！';
    const JUMP_TIME_INTERNAL = 5;

    /**
     * 邀请码生成key前缀
     */
    const INVITE_CODE_INCR = 'INVITE_CODE_INCR_';
    const INVITE_CODE_LEVEL_INCR = 'INVITE_CODE_LEVEL_INCR_';

    /**
     * 邀请码类型
     */
    const INVITE_CODE_TYPE_GENERAL_AGENT = 1;
    const INVITE_CODE_TYPE_FIRST_AGENT = 2;

    /**
     * 成功提示标题
     */
    const SUCCESS_PROMPT_OPEN_ROOM = '房间号';
    const SUCCESS_PROMPT_FIRST_AGENT_INVITE_CODE = '邀请码';

    /**
     * 佣金类型
     */
    const COMMISSION_TYPE_FIRST_TO_AGENT_RATE = 0.25;
    const COMMISSION_TYPE_GENERAL_TO_FIRST_RATE = 0.25;
    const COMMISSION_TYPE_GENERAL_TO_AGENT_RATE = 0.5;

    /**
     * 打款单类型
     */
    const CASH_ORDER_TYPE_GENERAL = 1;
    const CASH_ORDER_TYPE_FIRST = 2;

    const ROOM_CARD_RANDOMS = 8;

    const STAT_MAX_DAY = 30;

}