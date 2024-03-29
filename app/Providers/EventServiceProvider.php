<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //添加登录事件及对应监听器，一个事件可绑定多个监听器
        'App\Events\LoginEvent' => [
            'App\Listeners\LoginListener',
        ],
        'App\Events\ActionEvent' => [
            'App\Listeners\ActionListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
