<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Models\GamePlayer;
use App\Models\GamePlayerLogin;
use App\Models\GeneralAgents;

class GameLogic extends BaseLogic
{
    /**
     * @param     $params
     * @param     $page_size
     * @return mixed
     */
    public function getPlayerList($params, $page_size)
    {
        $where = [];

        if (isset($params['query_str']) && !empty($params['query_str'])) {
            // 分析query_str类型
            if (is_numeric($params['query_str'])) {
                // 邀请码查询
                $where['player_id'] = $params['query_str'];
            } else {
                // 姓名查询
                $where['player_name'] = $params['query_str'];
            }
        }

        if (!empty($where)) {
            $players = GamePlayer::where($where)->orderBy('id', 'desc')->simplePaginate($page_size);
        } else {
            $players = GamePlayer::orderBy('id', 'desc')->simplePaginate($page_size);
        }

        return $players;
    }

    /**
     * @param     $params
     * @param     $page_size
     * @return mixed
     */
    public function getPlayerLoginList($params, $page_size)
    {
        $where = [];

        if (isset($params['query_str']) && !empty($params['query_str'])) {
            // 分析query_str类型
            if (is_numeric($params['query_str'])) {
                // 邀请码查询
                $where['player_id'] = $params['query_str'];
            } else {
                // 姓名查询
                $where['player_name'] = $params['query_str'];
            }
        }

        if (!empty($where)) {
            $players = GamePlayerLogin::where($where)->orderBy('id', 'desc')->simplePaginate($page_size);
        } else {
            $players = GamePlayerLogin::orderBy('id', 'desc')->simplePaginate($page_size);
        }

        return $players;
    }
}