<?php

namespace App\Providers;

use App\Library\BContext;
use App\Library\BLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 初始化Context
        BContext::init();
        DB::listen(function ($query) {
            $db_log = [
                'statement' => $query->sql,
                'bind_params' => $query->bindings,
                'cost_time' => $query->time,
            ];
            BLogger::db($db_log);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
