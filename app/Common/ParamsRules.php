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
    const API_AGENT_ADD = '/api/agent/add';
    const API_AGENT_CANCEL = '/api/agent/cancel';
    public static $rules = [
        self::API_AGENT_ADD => [
            'id' => 'required|integer',
        ],
        self::API_AGENT_CANCEL => [
            'id' => 'required|integer',
        ],
    ];
}