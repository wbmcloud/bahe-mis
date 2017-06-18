<?php

namespace App\Providers;

use App\Logic\AccountLogic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            $db_log = [
                'sql' => $query->sql,
                'bind_params' => $query->bindings,
                'cost_time' => $query->time,
            ];
            Log::debug(json_encode($db_log));
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
