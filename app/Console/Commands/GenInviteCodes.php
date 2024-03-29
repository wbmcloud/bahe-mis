<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class GenInviteCodes extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:invite_code {city_id}';

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
        Redis::del(Constants::INVITE_CODE_INCR);

        $city_id = $this->argument('city_id');
        if (!empty($city_id)) {
            $city_ids = explode(',', $city_id);
            foreach ($city_ids as $city_id) {
                $this->insertRecord($city_id);
            }
        } else {
            $cities = City::get()->toArray();
            foreach ($cities as $city) {
                $this->insertRecord($city['city_id']);
            }
        }
    }

    protected function genInviteCode($city_id, $length = Constants::INVITE_CODE_LENGTH)
    {
        $invite_code = Redis::incr(Constants::INVITE_CODE_INCR . $city_id);
        return str_pad($invite_code, $length, 0, STR_PAD_LEFT);
    }

    protected function insertRecord($city_id)
    {
        $batch_insert_arr = [];
        for ($i = 0; $i < Constants::BATCH_SIZE; $i++) {
            $batch_insert_arr[] = [
                'city_id' => $city_id,
                'invite_code' => $this->genInviteCode($city_id),
                'type' => Constants::INVITE_CODE_TYPE_GENERAL_AGENT,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        DB::table('invite_code')->insert($batch_insert_arr);
    }
}
