<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class genInviteCodes extends Command
{

    const INVITE_CODE_LENGTH = 7;

    const INVITE_CODE_BATCH_SIZE = 100;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:invite_code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成邀请码';

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
        $batch_insert_arr = [];
        for ($i = 0; $i < self::INVITE_CODE_BATCH_SIZE; $i++) {
            $batch_insert_arr[] = [
                'invite_code' => $this->genInviteCode(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        DB::table('invite_code')->insert($batch_insert_arr);
    }

    protected function genInviteCode($length = self::INVITE_CODE_LENGTH)
    {
        $invite_code = '';
        for ($i = 0; $i < $length; $i++) {
            $invite_code .= mt_rand(0, 9);
        }
        return $invite_code;
    }
}
