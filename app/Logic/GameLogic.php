<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Exceptions\BaheException;
use App\Library\Protobuf\INNER_TYPE;
use App\Library\Protobuf\Protobuf;
use App\Library\TcpClient;
use App\Models\GamePlayer;
use App\Models\GamePlayerLogin;
use App\Models\GeneralAgents;

class GameLogic extends BaseLogic
{
    /**
     * @param $params
     * @param $page_size
     * @return \Illuminate\Contracts\Pagination\Paginator
     * @throws BaheException
     */
    public function getPlayerList($params, $page_size)
    {
        $where = [];

        if (isset($params['query_str']) && !empty($params['query_str'])) {
            // 分析query_str类型
            if (is_numeric($params['query_str'])) {
                // 邀请码查询
                $where['player_id'] = $params['query_str'];
                $players = GamePlayer::where($where)->orderBy('id', 'desc')->simplePaginate($page_size);
            } else {
                // 姓名查询
                $players = GamePlayer::where($where)
                    ->where('user_name', 'like', "%{$params['query_str']}%")
                    ->orderBy('id', 'desc')->simplePaginate($page_size);
            }
        } else {
            $players = GamePlayer::orderBy('id', 'desc')->simplePaginate($page_size);
        }

        foreach ($players as $key => &$player) {
            $query_player = $this->getQueryPlayer($player);
            $player->card_balance = !is_null($query_player) ? $query_player->getRoomCardCount() : '';
            $player->total_rounds = !is_null($query_player) ? $query_player->getTotalRounds() : '';
            $player->total_win_rounds = !is_null($query_player) ? $query_player->getTotalWinRounds() : '';
            $player->streak_wins = !is_null($query_player) ? $query_player->getStreakWins() : '';
        }

        return $players;
    }

    /**
     * @param $player_id
     * @return \App\Library\Protobuf\CommonProp|bool|null
     * @throws BaheException
     * @throws \Exception
     */
    public function getQueryPlayer($player)
    {
        $player_id = $player->player_id;
        $server_info = CityLogic::getServerInfo($player->server_id);

        if (empty($server_info)) {
            return;
        }

        $server['ip'] = $server_info['gmt_server_ip'];
        $server['port'] = $server_info['gmt_server_port'];

        // 调用gmt注册服务器
        $inner_meta_register_srv = Protobuf::packRegisterInnerMeta();
        $register_res            = TcpClient::callTcpService($inner_meta_register_srv, true, $server);
        if (Protobuf::unpackRegister($register_res)->getTypeT() !== INNER_TYPE::INNER_TYPE_REGISTER) {
            throw new BaheException(BaheException::GMT_SERVER_REGISTER_FAIL_CODE);
        }

        // 调用gmt查询玩家信息
        $inner_meta_query_player   = Protobuf::packQueryPlayerInnerMeta($player_id);
        $player          = Protobuf::unpackQueryPlayer(TcpClient::callTcpService($inner_meta_query_player, false, $server));
        if ($player == false) {
            return null;
        }

        return $player;
    }
}