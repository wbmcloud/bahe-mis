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
    const SUCCESS_CODE = 0;
    const FAIL_CODE = 2001001;
    const PERMISSION_FAIL_CODE = 2001002;
    const SYSTEM_ERROR_CODE = 2001003;
    const ACCOUNT_NOT_EXSIST_CODE = 2001004;
    const ROLE_NOT_EXSIST_CODE = 2001005;
    const RECHARGE_ROLE_NOT_AGENT_CODE = 2001006;
    const AGENT_NOT_RECHARGE_FOR_AGENT_CODE = 2001007;
    const USER_NOT_EXSIST_CODE = 2001008;
    const ACCOUNT_BALANCE_NOT_ENOUGH = 2001009;
    const PARAMS_INVALID = 2001010;
    const AGENT_NOT_EXSIST_CODE = 2001011;
    const INVITE_CODE_NOT_VALID_CODE = 2001012;
    const QUERY_STRING_NOT_EMPTY_CODE = 2001013;


    public static $error_msg = [
        self::SUCCESS_CODE => '操作成功',
        self::FAIL_CODE => '操作失败',
        self::PERMISSION_FAIL_CODE => '没有操作权限',
        self::SYSTEM_ERROR_CODE => '系统错误',
        self::ACCOUNT_NOT_EXSIST_CODE => '账户不存在',
        self::ROLE_NOT_EXSIST_CODE => '角色不存在',
        self::RECHARGE_ROLE_NOT_AGENT_CODE => '充值的不是合法的代理账号',
        self::AGENT_NOT_RECHARGE_FOR_AGENT_CODE => '代理不能为代理进行充值',
        self::USER_NOT_EXSIST_CODE => '用户不存在',
        self::ACCOUNT_BALANCE_NOT_ENOUGH => '账户余额不足',
        self::PARAMS_INVALID => '参数不合法',
        self::AGENT_NOT_EXSIST_CODE => '代理人不存在',
        self::INVITE_CODE_NOT_VALID_CODE => '邀请码不合法',
        self::QUERY_STRING_NOT_EMPTY_CODE => '查询字符串不能为空',
    ];

    public function __construct($code)
    {
        $message = self::$error_msg[$code];
        parent::__construct($message, $code);
    }
}
