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

class StatDateGeneralCashOrder extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:general_cash_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计打款单';

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
     * 具体使用方法：php artisan stat:cash_order
     * @return mixed
     */
    public function handle()
    {
        $last_week_day = Carbon::now()->subWeek();
        $last_week = $last_week_day->weekOfYear;

        $last_day_cash_orders = [];
        $last_day_cash_order = [
            'week' => $last_week,
            'month' => $last_week_day->month,
            'year' => $last_week_day->year,
            'type' => Constants::CASH_ORDER_TYPE_GENERAL,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ];
        $general_agents = User::where([
            'role_id' => Constants::ROLE_TYPE_GENERAL_AGENT,
            'status' => Constants::COMMON_ENABLE,
        ])->get()->toArray();
        foreach ($general_agents as $general_agent) {
            $last_day_cash_order['relation_id'] = $general_agent['id'];
            $last_day_cash_order['name'] = $general_agent['name'];

            $general_agent_logic = new GeneralAgentLogic();
            $start_of_week = $last_week_day->startOfWeek()->toDateTimeString();
            $end_of_week = $last_week_day->endOfWeek()->toDateTimeString();

            // 计算销售总监收入
            $general_sale_amount = $general_agent_logic->getFirstAgentIncomeList([
                'invite_code' => $general_agent['code'],
                'start_time' => $start_of_week,
                'end_time' => $end_of_week,
            ]);

            if (empty($general_sale_amount)) {
                $last_day_cash_order['amount'] = 0;
                $last_day_cash_orders[] = $last_day_cash_order;
                continue;
            }

            $general_sale_income = Utils::arraySum($general_sale_amount, 'sum') *
                Constants::COMMISSION_TYPE_GENERAL_TO_FIRST_RATE;

            // 计算销售代理收入
            $level_agent_amount = $general_agent_logic->getLevelAgentSaleAmount($general_agent['id'], $start_of_week, $end_of_week);
            $level_agent_income = Utils::arraySum($level_agent_amount->toArray(), 'sum') *
                Constants::COMMISSION_TYPE_GENERAL_TO_AGENT_RATE;


            // 合并收入
            $last_day_cash_order['amount'] = $general_sale_income + $level_agent_income;
            $last_day_cash_orders[] = $last_day_cash_order;
        }

        DB::table('cash_order')->insert($last_day_cash_orders);
    }
}
