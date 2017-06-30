<?php

namespace App\Listeners;

use App\Events\LoginEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;
use Zhuzhichao\IpLocationZh\Ip;

class LoginListener
{

    /**
     * Handle the event.
     *
     * @param  LoginEvent $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        //获取事件中保存的信息
        $user  = $event->getUser();
        $agent = $event->getAgent();
        $ip    = $event->getIp();

        //登录信息
        $login_info = [
            'ip'        => $ip,
            'user_id'   => $user->id,
            'user_name' => $user->name,
        ];

        $addresses = Ip::find($ip);
        $login_info['address'] = implode(' ', $addresses);
        // jenssegers/agent 的方法来提取agent信息
        $login_info['device']   = $agent->device(); //设备名称
        $browser                = $agent->browser();
        $login_info['browser']  = $browser . ' ' . $agent->version($browser); //浏览器
        $platform               = $agent->platform();
        $login_info['platform'] = $platform . ' ' . $agent->version($platform); //操作系统
        $login_info['language'] = implode(',', $agent->languages()); //语言
        //设备类型
        if ($agent->isTablet()) {
            // 平板
            $login_info['device_type'] = 'tablet';
        } else if ($agent->isMobile()) {
            // 便捷设备
            $login_info['device_type'] = 'mobile';
        } else if ($agent->isRobot()) {
            // 爬虫机器人
            $login_info['device_type'] = 'robot';
            $login_info['device']      = $agent->robot(); //机器人名称
        } else {
            // 桌面设备
            $login_info['device_type'] = 'desktop';
        }

        $login_info['created_at'] = Carbon::now();
        $login_info['updated_at'] = Carbon::now();

        //插入到数据库
        DB::table('login_log')->insert($login_info);
    }

}
