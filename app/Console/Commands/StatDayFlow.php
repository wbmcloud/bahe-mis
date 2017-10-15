<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Models\TransactionFlow;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StatDayFlow extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:day_flow {--all}';

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
                ])
                ->where('created_at', '>=', Constants::FEE_START_DATE)
                ->where('created_at', '<', $t)
                ->groupBy('day', 'recipient_type', 'recharge_type')
                ->selectRaw('substring(created_at, 1, 10) as day, recipient_type, recharge_type, sum(num) as amount')
                ->get()
                ->toArray();
        } else {
            // 计算当天房卡充值流水
            $flows = TransactionFlow::where([
                    'status' => Constants::COMMON_ENABLE,
                ])
                ->where('created_at', '>=', $y)
                ->where('created_at', '<', $t)
                ->groupBy('day', 'recipient_type', 'recharge_type')
                ->selectRaw('substring(created_at, 1, 10) as day, recipient_type, recharge_type, sum(num) as amount')
                ->get()
                ->toArray();
        }

        $day_flow = [];
        foreach ($flows as $flow) {
            if (isset($day_flow[$flow['day']])) {
                $row = $day_flow[$flow['day']];
            } else {
                $row = [];
            }
            if ($flow['recharge_type'] == COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD) {
                if ($flow['recipient_type'] == Constants::ROLE_TYPE_USER) {
                    $row['user_recharge_card_total'] = $flow['amount'];
                } else {
                    $row['agent_recharge_card_total'] = isset($row['agent_recharge_card_total']) ?
                        ($row['agent_recharge_card_total'] + $flow['amount']) : $flow['amount'];
                }
            }

            if ($flow['recharge_type'] == Constants::COMMAND_TYPE_OPEN_ROOM) {
                $row['open_room_card_total'] = $flow['amount'];
            }

            $day_flow[$flow['day']] = $row;
        }

        if (empty($day_flow)) {
            $carbon_day = Carbon::yesterday();
            $this->_genInsertRecord($value, $carbon_day);
            $day_flow[$value['day']] = $value;
        } else {
            foreach ($day_flow as $key => &$value) {
                $carbon_day = Carbon::createFromFormat('Y-m-d', $key);
                $this->_genInsertRecord($value, $carbon_day);
            }
        }

        DB::table('day_flow_stat')->insert($day_flow);
    }

    /**
     * @param $day_flow
     * @param $carbon
     * @return mixed
     */
    private function _genInsertRecord(&$day_flow, $carbon)
    {
        !isset($day_flow['user_recharge_card_total']) && ($day_flow['user_recharge_card_total'] = 0);
        !isset($day_flow['agent_recharge_card_total']) && ($day_flow['agent_recharge_card_total'] = 0);
        !isset($day_flow['open_room_card_total']) && ($day_flow['open_room_card_total'] = 0);
        $day_flow['day'] = $carbon->toDateString();
        $day_flow['week'] = $carbon->weekOfYear;
        $day_flow['month'] = $carbon->month;
        $day_flow['year'] = $carbon->year;
        $day_flow['created_at'] = Carbon::now()->toDateTimeString();
        $day_flow['updated_at'] = Carbon::now()->toDateTimeString();
        return $day_flow;
    }
}
