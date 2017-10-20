<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Models\DayAgentStat;
use App\Models\DayFlowStat;
use App\Models\GeneralAgents;

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