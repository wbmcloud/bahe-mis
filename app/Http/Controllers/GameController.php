<?php

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Logic\GameLogic;

class GameController extends Controller
{
    /**
     * 角色列表
     */
    public function playerList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        $game_logic = new GameLogic();

        return [
            'players' => $game_logic->getPlayerList($this->params, $page_size)
        ];
    }
}
