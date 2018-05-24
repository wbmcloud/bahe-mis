<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Common\Utils;
use App\Models\City;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class GenUserUniqueKey extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:user_uk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成用户唯一id';

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
     * 具体使用方法：php artisan gen:invite_code
     * @return mixed
     */
    public function handle()
    {
        $users = User::get();
        foreach ($users as $user) {
            do {
                $uk = Utils::genUniqueKey(Constants::AGENT_UK_LEN);
                $agent = User::where([
                    'uk' => $uk
                ])->first();

            } while (!empty($agent));
            $user->uk = $uk;
            $user->save();
        }
    }
}
