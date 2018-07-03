<?php

namespace App\Console\Commands;

use App\Models\InviteCode;
use App\Models\User;
use Illuminate\Console\Command;

class FlushCode extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flush:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '刷新邀请码';

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
     * 具体使用方法：php artisan flush:code
     * @return mixed
     */
    public function handle()
    {
        $users = User::get();
        $invite_codes = InviteCode::get()->toArray();
        $invite_codes = array_map(function ($v) {
            $v['uk'] = $v['city_id'] . '-' . $v['invite_code'];
            return $v;
        }, $invite_codes);

        $invite_codes = array_column($invite_codes, null, 'uk');

        foreach ($users as $user) {
            unset($uk);
            unset($invite_uk);

            (!empty($user->code)) && ($uk = $user->city_id . '-' . $user->code);
            (!empty($user->invite_code)) && ($invite_uk = $user->city_id . '-' . $user->invite_code);

            if (isset($uk) && isset($invite_codes[$uk]) && !empty($invite_codes[$uk])) {
                $user->code_id = $invite_codes[$uk]['id'];
            }

            if (isset($invite_uk) && isset($invite_codes[$invite_uk]) && !empty($invite_codes[$invite_uk])) {
                $user->invite_code_id = $invite_codes[$invite_uk]['id'];
            }

            $user->save();
        }
    }
}
