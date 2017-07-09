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
    const ROLE_SUPER       = 'super';
    const ROLE_ADMIN       = 'admin';
    const ROLE_AGENT       = 'agent';
    const ROLE_FIRST_AGENT = 'first_agent';

    const ROLE_TYPE_USER        = 0;
    const ROLE_TYPE_SUPER       = 1;
    const ROLE_TYPE_ADMIN       = 2;
    const ROLE_TYPE_AGENT       = 3;
    const ROLE_TYPE_FIRST_AGENT = 4;

    public static $transaction_type = [
        COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD => '房卡',
        COMMAND_TYPE::COMMAND_TYPE_RECHARGE  => '钻石',
        COMMAND_TYPE::COMMAND_TYPE_HUANLEDOU => '欢乐豆',
    ];

    public static $recharge_role_type = [
        self::ROLE_SUPER => self::ROLE_TYPE_SUPER,
        self::ROLE_ADMIN => self::ROLE_TYPE_ADMIN,
        self::ROLE_AGENT => self::ROLE_TYPE_AGENT,
        self::ROLE_FIRST_AGENT => self::ROLE_TYPE_FIRST_AGENT,
    ];

    public static $role_type = [
        self::ROLE_TYPE_SUPER => '超级管理员',
        self::ROLE_TYPE_ADMIN => '管理员',
        self::ROLE_TYPE_AGENT => '代理',
        self::ROLE_TYPE_FIRST_AGENT => '一级代理',
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
    const INVITE_CODE_LENGTH     = 7;
    const INVITE_CODE_BATCH_SIZE = 100;

    const ROOM_CARD_ITEM_ID = 13303809;

    const OPEN_ROOM_CARD_REDUCE = 1;

    public static $agreement_uri = [
        ParamsRules::IF_USER_AGREEMENT,
        ParamsRules::IF_USER_AGREE,
    ];

    const ADD_USER_TYPE_ADMIN       = 1;
    const ADD_USER_TYPE_AGENT       = 2;
    const ADD_USER_TYPE_FIRST_AGENT = 3;

    public static $recharge_role = [
        self::ROLE_AGENT,
        self::ROLE_FIRST_AGENT,
    ];

    public static $admin_role = [
        self::ROLE_SUPER,
        self::ROLE_ADMIN
    ];

    public static $level_agent = [
        self::ROLE_FIRST_AGENT,
    ];

    const COMMAND_TYPE_OPEN_ROOM = 11;

    const ROOM_CARD_PRICE = 2.5;

    /**
     * 代理级别类型
     */

    const AGENT_LEVEL_FIRST  = 1;
    const AGENT_LEVEL_SECOND = 2;
}