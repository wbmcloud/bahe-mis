<?php

namespace App\Console\Commands;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Console\Command;

class SyncLastLoginTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:last_login_time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步最近登录时间';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $process_user_login_log = [];
        $login_logs = LoginLog::get()->toArray();
        foreach ($login_logs as $login_log) {
            if (!isset($process_user_login_log['user_id'])) {
                $process_user_login_log[$login_log['user_id']]['user_id'] = $login_log['user_id'];
                $process_user_login_log[$login_log['user_id']]['user_name'] = $login_log['user_name'];
                $process_user_login_log[$login_log['user_id']]['created_at'] = $login_log['created_at'];
            }

            if (isset($process_user_login_log['user_id']['created_at']) &&
                $process_user_login_log[$login_log['user_id']]['created_at'] < $login_log['created_at']) {
                $process_user_login_log[$login_log['user_id']]['created_at'] = $login_log['created_at'];
            }
        }
        $users = User::get();
        foreach ($users as $user) {
            if (isset($process_user_login_log[$user->id])) {
                $user->last_login_time = $process_user_login_log[$user->id]['created_at'];
                $user->save();
            }
        }
    }
}
