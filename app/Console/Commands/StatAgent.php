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

class StatAgent extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:agent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日新增代理统计';

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
        $agents = User::whereIn('role_id', Constants::$agent_role_type)
            ->where('created_at', '>=', $y)
            ->where('created_at', '<', $t)
            ->groupBy('role_id')
            ->selectRaw('role_id, count(id) as amount')
            ->get()
            ->toArray();

        $agent_stat = [];
        foreach ($agents as $agent) {
            switch ($agent['role_id']) {
                case Constants::ROLE_TYPE_AGENT:
                    $agent_stat['agent_add_total'] = $agent['amount'];
                    break;
                case Constants::ROLE_TYPE_FIRST_AGENT:
                    $agent_stat['first_agent_add_total'] = $agent['amount'];
                    break;
                case Constants::ROLE_TYPE_GENERAL_AGENT:
                    $agent_stat['general_agent_add_total'] = $agent['amount'];
                    break;
            }
        }

        $agent_stat['day'] = Carbon::now()->format('Ymd');
        $agent_stat['week'] = Carbon::yesterday()->weekOfYear;
        $agent_stat['month'] = Carbon::yesterday()->month;
        $agent_stat['year'] = Carbon::yesterday()->year;
        $agent_stat['created_at'] = Carbon::now()->toDateTimeString();
        $agent_stat['updated_at'] = Carbon::now()->toDateTimeString();


        DB::table('day_agent_stat')->insert($agent_stat);
    }
}
