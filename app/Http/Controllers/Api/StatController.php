<?php

namespace App\Http\Controllers\Api;

use App\Common\Constants;
use App\Http\Controllers\Controller;
use App\Logic\StatLogic;

class StatController extends Controller
{
    /**
     * 代理数据统计
     */
    public function agent()
    {
        $stat_logic = new StatLogic();

        return $stat_logic->getStatAgentList(Constants::STAT_MAX_DAY);
    }

    /**
     * 流水统计
     */
    public function flow()
    {
        $stat_logic = new StatLogic();

        return $stat_logic->getStatFlowList(Constants::STAT_MAX_DAY);
    }
}
