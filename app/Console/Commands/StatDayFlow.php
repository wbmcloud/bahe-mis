<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Common\Utils;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Logic\FirstAgentLogic;
use App\Logic\GeneralAgentLogic;
use App\Models\CashOrder;
use App\Models\TransactionFlow;
use App\Models\User;
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
    protected $signature = 'stat:day_flow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日数据统计';

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

        // 计算当天房卡充值流水
        $flows = TransactionFlow::where([
                'status' => Constants::COMMON_ENABLE,
            ])
            ->where('created_at', '>=', $y)
            ->where('created_at', '<', $t)
            ->groupBy('recipient_type', 'recharge_type')
            ->selectRaw('recipient_type, recharge_type, sum(num) as amount')
            ->get()
            ->toArray();

        $day_flow = [];
        foreach ($flows as $flow) {
            if ($flow['recharge_type'] == COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD) {
                if ($flow['recipient_type'] == Constants::ROLE_TYPE_USER) {
                    $day_flow['user_recharge_card_total'] = $flow['amount'];
                } else {
                    $agent_day_flow[] = $flow['amount'];
                }
            }

            if ($flow['recharge_type'] == Constants::COMMAND_TYPE_OPEN_ROOM) {
                $day_flow['open_room_card_total'] = $flow['amount'];
            }
        }

        $day_flow['agent_recharge_card_total'] = array_sum($agent_day_flow);
        $day_flow['day'] = Carbon::yesterday()->format('Ymd');
        $day_flow['week'] = Carbon::yesterday()->weekOfYear;
        $day_flow['month'] = Carbon::yesterday()->month;
        $day_flow['year'] = Carbon::yesterday()->year;
        $day_flow['created_at'] = Carbon::now()->toDateTimeString();
        $day_flow['updated_at'] = Carbon::now()->toDateTimeString();


        DB::table('day_flow_stat')->insert($day_flow);
    }
}
