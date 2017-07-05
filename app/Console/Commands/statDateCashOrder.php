<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Models\CashOrder;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class statDateCashOrder extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:cash_order';

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
        $yesterday = Carbon::yesterday();
        $last_day_cash_orders = [];
        $last_day_cash_order = [
            'day' => $yesterday->dayOfYear,
            'week' => $yesterday->weekOfYear,
            'month' => $yesterday->month,
            'year' => $yesterday->year,
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
                'invite_code' => $first_agent['invite_code'],
                'role_id' => Constants::ROLE_TYPE_AGENT,
            ])->get()->toArray();
            if (empty($agents)) {
                $last_day_cash_order['amount'] = 0;
                $last_day_cash_orders[] = $last_day_cash_order;
                continue;
            }
            $agent_total_flows = [];
            foreach ($agents as $agent) {
                $agent_total_flows[] = TransactionFlow::where([
                    'recipient_id' => $agent['id'],
                    'recharge_type' => COMMAND_TYPE::COMMAND_TYPE_ROOM_CARD,
                ])->get()->sum('num');
            }
            $last_day_cash_order['amount'] = array_sum($agent_total_flows);
            $last_day_cash_orders[] = $last_day_cash_order;
        }

        DB::table('cash_order')->insert($last_day_cash_orders);
    }
}
