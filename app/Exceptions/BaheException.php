<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/11
 * Time: 下午12:37
 */

namespace App\Exceptions;

use App\Library\Protobuf\COMMAND_ERROR_CODE;
use App\Library\Protobuf\COMMAND_TYPE;

class BaheException extends \Exception
{
    const SUCCESS_CODE                      = 0;
    const FAIL_CODE                         = 2001001;
    const PERMISSION_FAIL_CODE              = 2001002;
    const SYSTEM_ERROR_CODE                 = 2001003;
    const ACCOUNT_NOT_EXIST_CODE            = 2001004;
    const ROLE_NOT_EXIST_CODE               = 2001005;
    const AGENT_NOT_VALID_CODE              = 2001006;
    const AGENT_NOT_RECHARGE_FOR_AGENT_CODE = 2001007;
    const USER_NOT_EXIST_CODE               = 2001008;
    const ACCOUNT_BALANCE_NOT_ENOUGH        = 2001009;
    const PARAMS_INVALID                    = 2001010;
    const AGENT_NOT_EXIST_CODE              = 2001011;
    const INVITE_CODE_NOT_VALID_CODE        = 2001012;
    const QUERY_STRING_NOT_EMPTY_CODE       = 2001013;
    const GMT_SERVER_REGISTER_FAIL_CODE     = 2001014;
    const GMT_SERVER_RECHARGE_FAIL_CODE     = 2001015;
    const GMT_SERVER_OPEN_ROOM_FAIL_CODE       = 2001016;
    const ROLE_NOT_VALID_CODE                  = 2001017;
    const USER_PASSWORD_CONFIRM_NOT_VALID_CODE = 2001018;
    const USER_PASSWORD_OLD_NOT_VALID_CODE     = 2001019;
    const METHOD_NOT_EXIST_CODE                = 2001020;
    const TYPE_NOT_VALID_CODE                  = 2001021;
    const INVITE_CODE_USED_CODE             = 2001022;
    const CASH_ORDER_NOT_FOUND_CODE         = 2001023;
    const LOGIN_USER_NAME_OR_PASSWD_INVALID = 2001024;
    const RESOURCE_NOT_FOUND                = 2001025;
    const INVITE_CODE_NOT_USED_CODE         = 2001026;
    const USER_EXIST_CODE                   = 2001027;
    const LOGIN_USER_ACCOUNT_FROZEN         = 2001028;

    const CITY_NOT_VALID_CODE = 2001029;
    const GMT_BIND_PLAYER_FAIL_CODE = 2001030;
    const GAME_SERVER_NOT_FOUND_CODE = 2001031;
    const BIND_AGENT_NOT_VALID_CODE = 2001032;


    public static $error_msg = [
        self::SUCCESS_CODE                      => '操作成功',
        self::FAIL_CODE                         => '操作失败',
        self::PERMISSION_FAIL_CODE              => '没有操作权限',
        self::SYSTEM_ERROR_CODE                 => '系统错误',
        self::ACCOUNT_NOT_EXIST_CODE            => '账户不存在',
        self::ROLE_NOT_EXIST_CODE               => '角色不存在',
        self::AGENT_NOT_VALID_CODE              => '代理不合法',
        self::AGENT_NOT_RECHARGE_FOR_AGENT_CODE => '没有给代理充值的权限',
        self::USER_NOT_EXIST_CODE               => '用户不存在',
        self::ACCOUNT_BALANCE_NOT_ENOUGH        => '账户余额不足',
        self::PARAMS_INVALID                    => '参数不合法',
        self::AGENT_NOT_EXIST_CODE              => '代理人不存在',
        self::INVITE_CODE_NOT_VALID_CODE        => '邀请码不合法',
        self::QUERY_STRING_NOT_EMPTY_CODE       => '查询字符串不能为空',
        self::GMT_SERVER_REGISTER_FAIL_CODE     => 'GMT服务器注册失败',
        self::GMT_SERVER_RECHARGE_FAIL_CODE        => '充值失败',
        self::GMT_SERVER_OPEN_ROOM_FAIL_CODE       => '代开房失败',
        self::ROLE_NOT_VALID_CODE                  => '角色不合法',
        self::USER_PASSWORD_CONFIRM_NOT_VALID_CODE => '两次输入的新密码不一致',
        self::USER_PASSWORD_OLD_NOT_VALID_CODE     => '输入的原密码不正确',
        self::METHOD_NOT_EXIST_CODE                => '方法不存在',
        self::TYPE_NOT_VALID_CODE                  => '类型不合法',
        self::INVITE_CODE_USED_CODE                => '邀请码已经使用',
        self::CASH_ORDER_NOT_FOUND_CODE            => '未找到打款单',
        self::LOGIN_USER_NAME_OR_PASSWD_INVALID    => '用户名或者密码有误！',
        self::RESOURCE_NOT_FOUND                   => '未找到资源',
        self::INVITE_CODE_NOT_USED_CODE            => '邀请码未使用',
        self::USER_EXIST_CODE                      => '用户已经存在',
        self::LOGIN_USER_ACCOUNT_FROZEN            => '由于您的账号长期未登陆，为了保护您的账号安全，您的账号已自动进行冻结。如需正常使用请联系微信客服xlcyqp001',
        self::CITY_NOT_VALID_CODE                  => '城市不合法',
        self::GMT_BIND_PLAYER_FAIL_CODE            => '绑定角色失败',
        self::GAME_SERVER_NOT_FOUND_CODE           => '未找到游戏服务',
        self::BIND_AGENT_NOT_VALID_CODE            => '绑定的代理不合法'
    ];


    public static $gmt_error_msg = [
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_SUCCESS          => '成功',
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_NO_PERMISSION    => '没有权限',
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_PARA             => '参数错误',
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_NO_ACCOUNT       => '没有账号数据',
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_NO_PLAYER        => '没有玩家数据',
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_PLAYER_ONLINE    => '玩家在线',
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_PLAYER_OFFLINE   => '玩家离线',
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_ITEM_NOT_FOUND   => '物品未能找到',
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_SERVER_NOT_FOUND => '未能找到服务器',
        COMMAND_ERROR_CODE::COMMAND_ERROR_CODE_ASSET_NOT_FOUND  => '未能找到相关数据，由于策划配置造成',

    ];

    public function __construct($code, $message = null)
    {
        if (is_null($message)) {
            $message = self::$error_msg[$code];
        }
        parent::__construct($message, $code);
    }

    public static function getErrorMsg($error)
    {
        $error = !empty($error) ? json_decode($error, true) : [];

        if (empty($error)) {
            return '';
        }

        if (isset($error['error_msg'])) {
            return $error['error_msg'];
        }

        if (isset(self::$gmt_error_msg[$error['error_code']])) {
            return self::$gmt_error_msg[$error['error_code']];
        }

        return '';
    }
}
