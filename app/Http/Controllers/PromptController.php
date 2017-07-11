<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Common\ParamsRules;

class PromptController extends Controller
{

    public function index()
    {
        $data = [
            'message'  => Constants::OPERATOR_PROMPT_NOT_PERMISSION_TEXT,
            'jump_url'      => '/',
            'jump_time' => Constants::JUMP_TIME_INTERNAL,
        ];

        //验证参数
        if (!empty(session('message')) &&
            !empty(session('jump_url')) &&
            !empty(session('jump_time'))) {
            $data = [
                'message'  => session('message'),
                'jump_url'      => session('jump_url'),
                'jump_time' => session('jump_time'),
            ];
        }

        return view(ParamsRules::IF_PROMPT, $data);
    }
}