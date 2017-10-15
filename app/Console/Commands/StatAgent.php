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
    protected $signature = 'stat:agent {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日新增各级代理统计';

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
            // 统计历史数据
            $agents = User::whereIn('role_id', Constants::$agent_role_type)
                ->where('created_at', '>=', Constants::FEE_START_DATE)
                ->where('created_at', '<', $t)
                ->groupBy('day', 'role_id')
                ->selectRaw('substring(created_at, 1, 10) as day, role_id, count(id) as amount')
                ->get()
                ->toArray();
        } else {
            // 计算当天房卡充值流水
            $agents = User::whereIn('role_id', Constants::$agent_role_type)
                ->where('created_at', '>=', $y)
                ->where('created_at', '<', $t)
                ->groupBy('day', 'role_id')
                ->selectRaw('substring(created_at, 1, 10) as day, role_id, count(id) as amount')
                ->get()
                ->toArray();
        }
        $agent_stat = [];
        foreach ($agents as $agent) {
            if (isset($agent_stat[$agent['day']])) {
                $row = $agent_stat[$agent['day']];
            } else {
                $row = [];
            }
            switch ($agent['role_id']) {
                case Constants::ROLE_TYPE_AGENT:
                    $row['agent_add_total'] = $agent['amount'];
                    break;
                case Constants::ROLE_TYPE_FIRST_AGENT:
                    $row['first_agent_add_total'] = $agent['amount'];
                    break;
                case Constants::ROLE_TYPE_GENERAL_AGENT:
                    $row['general_agent_add_total'] = $agent['amount'];
                    break;
            }

            $agent_stat[$agent['day']] = $row;
        }

        if (empty($agent_stat)) {
            $carbon_day = Carbon::yesterday();
            $this->_genInsertRecord($value, $carbon_day);
            $agent_stat[$value['day']] = $value;
        } else {
            foreach ($agent_stat as $key => &$value) {
                $carbon_day = Carbon::createFromFormat('Y-m-d', $key);
                $this->_genInsertRecord($value, $carbon_day);
            }
        }

        DB::table('day_agent_stat')->insert(array_values($agent_stat));
    }

    /**
     * @param $value
     * @param $carbon
     */
    private function _genInsertRecord(&$value, $carbon)
    {
        !isset($value['agent_add_total']) && ($value['agent_add_total'] = 0);
        !isset($value['first_agent_add_total']) && ($value['first_agent_add_total'] = 0);
        !isset($value['general_agent_add_total']) && ($value['general_agent_add_total'] = 0);
        $value['day'] = $carbon->toDateString();
        $value['week'] = $carbon->weekOfYear;
        $value['month'] = $carbon->month;
        $value['year'] = $carbon->year;
        $value['created_at'] = Carbon::now()->toDateTimeString();
        $value['updated_at'] = Carbon::now()->toDateTimeString();
        return $value;
    }
}
