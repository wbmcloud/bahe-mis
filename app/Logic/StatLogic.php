<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Common\Constants;
use App\Console\Commands\StatAgent;
use App\Console\Commands\StatDayFlow;
use App\Exceptions\BaheException;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Models\CashOrder;
use App\Models\DayAgentStat;
use App\Models\DayFlowStat;
use App\Models\GamePlayer;
use App\Models\GamePlayerLogin;
use App\Models\GeneralAgents;
use App\Models\InviteCode;
use App\Models\TransactionFlow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class StatLogic extends BaseLogic
{
    /**
     * @param $size
     * @return mixed
     */
    public function getStatAgentList($size)
    {
        $day_agents = DayAgentStat::orderBy('id', 'desc')->take($size)->get()->toArray();
        array_multisort(array_column($day_agents, 'id'), SORT_ASC, $day_agents);
        return [
            'list' => $day_agents
        ];
    }

    /**
     * @param $size
     * @return array
     */
    public function getStatFlowList($size)
    {
        $flows = DayFlowStat::orderBy('id', 'desc')->take($size)->get()->toArray();
        array_multisort(array_column($flows, 'id'), SORT_ASC, $flows);
        return [
            'list' => $flows
        ];
    }

}