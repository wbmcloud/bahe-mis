<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Models\TransactionFlow;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StatDayAgentFlow extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:day_agent_flow {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日充值数据统计';

    /**
     * StatDayFlow constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * 具体使用方法：php artisan stat:cash_order
     * @return mixed
     */
    public function handle()
    {
        $y = Carbon::yesterday()->toDateTimeString();
        $t = Carbon::today()->toDateTimeString();

        $option = $this->option('all');

        if ($option) {
            // 计算当天房卡充值流水
            $flows = TransactionFlow::where([
                    'status' => Constants::COMMON_ENABLE,
                    'recharge_type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                ])
                ->where('created_at', '>=', Constants::FEE_START_DATE)
                ->where('created_at', '<', $t)
                ->whereIn('recipient_type', Constants::$agent_role_type)
                ->groupBy('day', 'city_id')
                ->selectRaw('substring(created_at, 1, 10) as day, city_id, sum(num) as amount')
                ->get()
                ->toArray();
        } else {
            // 计算当天房卡充值流水
            $flows = TransactionFlow::where([
                    'status' => Constants::COMMON_ENABLE,
                    'recharge_type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                ])
                ->where('created_at', '>=', $y)
                ->where('created_at', '<', $t)
                ->whereIn('recipient_type', Constants::$agent_role_type)
                ->groupBy('day', 'city_id')
                ->selectRaw('substring(created_at, 1, 10) as day, city_id, sum(num) as amount')
                ->get()
                ->toArray();
        }

        $day_flow = [];
        foreach ($flows as $flow) {
            $k = $flow['day'] . '-' . $flow['city_id'];
            if (isset($day_flow[$k])) {
                $row = $day_flow[$k];
            } else {
                $row['city_id'] = $flow['city_id'];
                $row['day'] = $flow['day'];
            }

            $row['total'] = $flow['amount'];

            $day_flow[$k] = $row;
        }

        if (empty($day_flow)) {
            $carbon_day = Carbon::yesterday();
            $this->_genInsertRecord($value, $carbon_day);
            $day_flow[$value['day']] = $value;
        } else {
            foreach ($day_flow as $key => &$value) {
                $carbon_day = Carbon::createFromFormat('Y-m-d', $value['day']);
                $this->_genInsertRecord($value, $carbon_day);
            }
        }

        DB::table('day_agent_flow_stat')->insert($day_flow);
    }

    /**
     * @param $day_flow
     * @param $carbon
     * @return mixed
     */
    private function _genInsertRecord(&$day_flow, $carbon)
    {
        !isset($day_flow['total']) && ($day_flow['total'] = 0);
        $day_flow['day'] = $carbon->toDateString();
        $day_flow['week'] = $carbon->weekOfYear;
        $day_flow['month'] = $carbon->month;
        $day_flow['year'] = $carbon->year;
        $day_flow['created_at'] = Carbon::now()->toDateTimeString();
        $day_flow['updated_at'] = Carbon::now()->toDateTimeString();
        return $day_flow;
    }
}
