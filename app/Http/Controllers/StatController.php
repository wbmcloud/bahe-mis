<?php

namespace App\Http\Controllers;

use App\Logic\UserLogic;

class StatController extends Controller
{
    /**
     * 代理数据统计
     */
    public function agent()
    {
        return [];
    }

    /**
     * 流水统计
     */
    public function flow()
    {
        // 管理员和超级管理员
        $user_logic = new UserLogic();
        $cities     = $user_logic->getOpenCities();

        return [
            'cities' => $cities
        ];
    }

    /**
     * 代理充值流水
     * @return array
     */
    public function agentFlow()
    {
        // 管理员和超级管理员
        $user_logic = new UserLogic();
        $cities     = $user_logic->getOpenCities();

        return [
            'cities' => $cities
        ];
    }

    /**
     * 局数统计
     */
    public function rounds()
    {
        // 管理员和超级管理员
        $user_logic = new UserLogic();
        $cities     = $user_logic->getOpenCities();

        return [
            'cities' => $cities
        ];
    }

    /**
     * 游戏日活DAU统计
     */
    public function dau()
    {
        // 管理员和超级管理员
        $user_logic = new UserLogic();
        $cities     = $user_logic->getOpenCities();

        return [
            'cities' => $cities
        ];
    }

    /**
     * 游戏日活WAU统计
     */
    public function wau()
    {
        // 管理员和超级管理员
        $user_logic = new UserLogic();
        $cities     = $user_logic->getOpenCities();

        return [
            'cities' => $cities
        ];
    }

    /**
     * 游戏日活MAU统计
     */
    public function mau()
    {
        // 管理员和超级管理员
        $user_logic = new UserLogic();
        $cities     = $user_logic->getOpenCities();

        return [
            'cities' => $cities
        ];
    }

}
