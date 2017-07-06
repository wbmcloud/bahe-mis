<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/11
 * Time: 下午12:37
 */

namespace App\Exceptions;

class SlException extends \Exception
{
    const SUCCESS_CODE                         = 0;
    const FAIL_CODE                            = 2001001;
    const PERMISSION_FAIL_CODE                 = 2001002;
    const SYSTEM_ERROR_CODE                    = 2001003;
    const ACCOUNT_NOT_EXIST_CODE               = 2001004;
    const ROLE_NOT_EXIST_CODE                  = 2001005;
    const RECHARGE_ROLE_NOT_AGENT_CODE         = 2001006;
    const AGENT_NOT_RECHARGE_FOR_AGENT_CODE    = 2001007;
    const USER_NOT_EXIST_CODE                  = 2001008;
    const ACCOUNT_BALANCE_NOT_ENOUGH           = 2001009;
    const PARAMS_INVALID                       = 2001010;
    const AGENT_NOT_EXIST_CODE                 = 2001011;
    const INVITE_CODE_NOT_VALID_CODE           = 2001012;
    const QUERY_STRING_NOT_EMPTY_CODE          = 2001013;
    const GMT_SERVER_REGISTER_FAIL_CODE        = 2001014;
    const GMT_SERVER_RECHARGE_FAIL_CODE        = 2001015;
    const GMT_SERVER_OPEN_ROOM_FAIL_CODE       = 2001016;
    const ROLE_NOT_VALID_CODE                  = 2001017;
    const USER_PASSWORD_CONFIRM_NOT_VALID_CODE = 2001018;
    const USER_PASSWORD_OLD_NOT_VALID_CODE     = 2001019;
    const METHOD_NOT_EXIST_CODE                = 2001020;
    const TYPE_NOT_VALID_CODE                  = 2001021;
    const INVITE_CODE_USED_CODE                = 2001022;
    const CASH_ORDER_NOT_FOUND_CODE            = 2001023;


    public static $error_msg = [
        self::SUCCESS_CODE                         => '操作成功',
        self::FAIL_CODE                            => '操作失败',
        self::PERMISSION_FAIL_CODE                 => '没有操作权限',
        self::SYSTEM_ERROR_CODE                    => '系统错误',
        self::ACCOUNT_NOT_EXIST_CODE               => '账户不存在',
        self::ROLE_NOT_EXIST_CODE                  => '角色不存在',
        self::RECHARGE_ROLE_NOT_AGENT_CODE         => '充值的不是合法的代理账号',
        self::AGENT_NOT_RECHARGE_FOR_AGENT_CODE    => '没有给代理充值的权限',
        self::USER_NOT_EXIST_CODE                  => '用户不存在',
        self::ACCOUNT_BALANCE_NOT_ENOUGH           => '账户余额不足',
        self::PARAMS_INVALID                       => '参数不合法',
        self::AGENT_NOT_EXIST_CODE                 => '代理人不存在',
        self::INVITE_CODE_NOT_VALID_CODE           => '邀请码不合法',
        self::QUERY_STRING_NOT_EMPTY_CODE          => '查询字符串不能为空',
        self::GMT_SERVER_REGISTER_FAIL_CODE        => 'GMT服务器注册失败',
        self::GMT_SERVER_RECHARGE_FAIL_CODE        => '充值失败',
        self::GMT_SERVER_OPEN_ROOM_FAIL_CODE       => '代开房失败',
        self::ROLE_NOT_VALID_CODE                  => '角色不合法',
        self::USER_PASSWORD_CONFIRM_NOT_VALID_CODE => '两次输入的新密码不一致',
        self::USER_PASSWORD_OLD_NOT_VALID_CODE     => '输入的原密码不正确',
        self::METHOD_NOT_EXIST_CODE                => '方法不存在',
        self::TYPE_NOT_VALID_CODE                  => '类型不合法',
        self::INVITE_CODE_USED_CODE                => '邀请码已经使用',
        self::CASH_ORDER_NOT_FOUND_CODE            => '未找到打款单',
    ];

    public function __construct($code, $message = null)
    {
        if (is_null($message)) {
            $message = self::$error_msg[$code];
        }
        parent::__construct($message, $code);
    }
}
