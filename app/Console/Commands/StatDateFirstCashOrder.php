<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Logic\FirstAgentLogic;
use App\Models\CashOrder;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StatDateFirstCashOrder extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:first_cash_order';

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
            'type' => Constants::CASH_ORDER_TYPE_FIRST,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ];
        $first_agents = User::where([
            'role_id' => Constants::ROLE_TYPE_FIRST_AGENT,
            'status' => Constants::COMMON_ENABLE,
        ])->get()->toArray();
        foreach ($first_agents as $first_agent) {
            $last_day_cash_order['relation_id'] = $first_agent['id'];
            $last_day_cash_order['name'] = $first_agent['name'];

            $agents = User::where([
                'invite_code' => $first_agent['code'],
                'role_id' => Constants::ROLE_TYPE_AGENT,
            ])->get()->toArray();
            if (empty($agents)) {
                $last_day_cash_order['amount'] = 0;
                $last_day_cash_orders[] = $last_day_cash_order;
                continue;
            }

            $first_agent_logic = new FirstAgentLogic();
            $start_of_week = $last_week_day->startOfWeek()->toDateTimeString();
            $end_of_week = $last_week_day->endOfWeek()->toDateTimeString();
            $level_agent_income = $first_agent_logic->getLevelAgentSaleAmount($first_agent['id'], $start_of_week, $end_of_week);
            $level_agent_income = array_column($level_agent_income->toArray(), 'sum');
            $last_day_cash_order['amount'] = array_sum($level_agent_income) * Constants::COMMISSION_TYPE_FIRST_TO_AGENT_RATE;
            $last_day_cash_orders[] = $last_day_cash_order;
        }

        DB::table('cash_order')->insert($last_day_cash_orders);
    }
}
