<?php

namespace App\Console\Commands;

use App\Models\DayGamePlayerLoginLog;
use App\Models\GamePlayer;
use App\Models\GameAccount;
use Carbon\Carbon;
use IDCT\Networking\Ssh\Credentials;
use IDCT\Networking\Ssh\SftpClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SyncGamePlayerInfo extends Command
{
    /**
     * 游戏日志目录
     */
    const REMOTE_GAME_LOG_PATH = '/export/game/workdir/logs/';

    /**
     * 游戏角色日志文件名
     */
    const PLAYER_FILE_NAME = 'player';

    /**
     * 游戏账号日志文件名
     */
    const ACCOUNT_FILE_NAME = 'account';

    /**
     * 同步到中心日志服务器的日志收集地址
     */
    const CENTER_SERVER_LOG_PATH = '/sdb1/game/logs/';

    /**
     * SSH认证的用户名
     */
    // const SSH_AUTH_USER_NAME = 'game';
    const SSH_AUTH_USER_NAME = 'root';

    /**
     * SSH认证密码
     */
    const SSH_AUTH_PASSWORD = '!QAZ8ik,9ol.';

    /**
     * Public Key name
     */
    const SSH_PUBLIC_KEY = 'id_rsa.pub';

    /**
     * Private Key name
     */
    const SSH_PRIVATE_KEY = 'id_rsa';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:game_player';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步游戏角色信息';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new SftpClient();

        if (App::environment('production')) {
            $servers = Config::get('services.game_server.outer');
        } else {
            $servers = Config::get('services.game_server.inner');
        }

        foreach ($servers as $server) {
            $credentials = Credentials::withPublicKey(self::SSH_AUTH_USER_NAME,
                $this->getSshKeyPath(self::SSH_PUBLIC_KEY), $this->getSshKeyPath(self::SSH_PRIVATE_KEY));

            $client->setCredentials($credentials);
            $client->connect($server['host']);

            $center_server_player_log_path = $this->getCenterServerPlayerLogName($server['host']);
            $client->scpDownload($this->getRemotePlayerLogName($server['path']), $center_server_player_log_path);

            $center_server_account_log_path = $this->getCenterServerAccountLogName($server['host']);
            $client->scpDownload($this->getRemoteAccountLogName($server['path']), $center_server_account_log_path);

            $client->close();

            $this->processPlayerLog($center_server_player_log_path);
            $this->processAccountLog($center_server_account_log_path);
        }

        /*$p_path = '/Users/cloud/Documents/player_2017-10-25';
        $a_path = '/Users/cloud/Documents/account_2017-10-25';

        $this->processPlayerLog($p_path);
        $this->processAccountLog($a_path);*/

        $this->updatePlayerInfo();
    }

    /**
     * 获取当天远程游戏角色日志名
     * @param $path
     * @return string
     */
    protected function getRemotePlayerLogName($path = self::REMOTE_GAME_LOG_PATH)
    {
        return $path . self::PLAYER_FILE_NAME . '_' . Carbon::yesterday()->toDateString();
    }

    /**
     * 获取当天远程游戏角色日志名
     * @param $path
     * @return string
     */
    protected function getRemoteAccountLogName($path = self::REMOTE_GAME_LOG_PATH)
    {
        return $path . self::ACCOUNT_FILE_NAME . '_' . Carbon::yesterday()->toDateString();
    }

    /**
     * @param $key_name
     * @return string
     */
    protected function getSshKeyPath($key_name)
    {
        return '/root' . DIRECTORY_SEPARATOR . '.ssh/' . $key_name;
    }

    /**
     * @param $server_ip
     * @return string
     */
    protected function getCenterServerPlayerLogName($server_ip)
    {
        return self::CENTER_SERVER_LOG_PATH . self::PLAYER_FILE_NAME . '_' . $server_ip . '_' . Carbon::yesterday()->toDateString() . '.log';
    }

    /**
     * @param $server_ip
     * @return string
     */
    protected function getCenterServerAccountLogName($server_ip)
    {
        return self::CENTER_SERVER_LOG_PATH . self::ACCOUNT_FILE_NAME . '_' . $server_ip . '_' . Carbon::yesterday()->toDateString() . '.log';
    }

    /**
     * @param $file_name
     * @return \Generator
     */
    protected function yieldReadLog($file_name)
    {
        $handle = fopen($file_name, 'rb');

        while(feof($handle) === false) {
            yield fgets($handle);
        }

        fclose($handle);
    }

    /**
     * 日志解析处理
     * @param $file_name
     * @return bool
     */
    protected function processPlayerLog($file_name)
    {
        if (!file_exists($file_name)) {
            return false;
        }
        //$lines = file($file_name, FILE_SKIP_EMPTY_LINES);
        $lines = $this->yieldReadLog($file_name);

        //$player_login_logs = [];
        foreach ($lines as $idx => $line) {
            if (empty($line)) {
                continue;
            }
            $arr = explode(' ', $line);
            $player_log = json_decode($arr[5], true);
            if (!isset($player_log['common_prop'])) {
                continue;
            }

            if (!isset($player_log['common_prop']['name']) ||
                !isset($player_log['account']) ||
                !isset($player_log['server_id'])) {
                continue;
            }

            // 查询是否已经存在角色
            $game_player = GamePlayer::where('player_id', $player_log['common_prop']['player_id'])->first();
            if (empty($game_player)) {
                $game_player = new GamePlayer();
                $game_player->player_id = $player_log['common_prop']['player_id'];
            }
            $game_player->player_name = $player_log['common_prop']['name'];
            $game_player->user_id = $player_log['account'];
            $game_player->server_id = $player_log['server_id'];
            $game_player->save();

            // 登录日志
            $this->_insertPlayerLoginLog($player_log);
        }
    }

    protected function processAccountLog($file_name)
    {
        if (!file_exists($file_name)) {
            return false;
        }
        /*$lines = file($file_name, FILE_SKIP_EMPTY_LINES);
        if (empty($lines)) {
            return false;
        }*/
        $lines = $this->yieldReadLog($file_name);

        //$player_login_logs = [];
        foreach ($lines as $idx => $line) {
            if (empty($line)) {
                continue;
            }
            $arr = explode(' ', $line);
            $account_log = json_decode($arr[5], true);
            if (!isset($account_log['wechat'])) {
                if (empty($account_log['account']['username'])) {
                    continue;
                }
                // 查询是否已经存在角色
                $game_account = GameAccount::where('user_id', $account_log['account']['username'])->first();
                if (empty($game_account)) {
                    $game_account = new GameAccount();
                    $game_account->user_id = $account_log['account']['username'];
                }
            } else {
                if (empty($account_log['wechat']['openid'])) {
                    continue;
                }
                // 查询是否已经存在角色
                $game_account = GameAccount::where('user_id', $account_log['wechat']['openid'])->first();
                if (empty($game_account)) {
                    $game_account = new GameAccount();
                    $game_account->user_id = $account_log['wechat']['openid'];
                }
                $game_account->user_name = $account_log['wechat']['nickname'];
                $game_account->head_img_url = isset($account_log['wechat']['headimgurl']) ?
                    $account_log['wechat']['headimgurl'] : '';
            }
            isset($account_log['created_time']) && ($game_account->create_time = Carbon::createFromTimestamp($account_log['created_time'])->toDateTimeString());
            $game_account->client_ip = $account_log['client_info']['client_ip'];
            $game_account->save();
        }
    }

    protected function updatePlayerInfo()
    {
        $t = Carbon::today()->toDateTimeString();
        // 查询是否已经存在角色
        $game_players = GamePlayer::where('updated_at', '>', $t)->get();
        foreach ($game_players as $game_player) {
            if (!strpos($game_player->user_id, 'guest')) {
                $game_account = GameAccount::where('user_id', $game_player->user_id)->first();
                if (empty($game_account)) {
                    continue;
                }
                $game_player->user_name = $game_account->user_name;
            }
            $game_player->client_ip = $game_account->client_ip;
            $game_player->create_time = $game_account->create_time;
            $game_player->save();
        }
    }

    /**
     * @param $login_log
     * @return mixed
     */
    private function _insertPlayerLoginLog($login_log)
    {
        $carbon = Carbon::yesterday();

        $day_game_player_login_log = DayGamePlayerLoginLog::where([
            'day' => $carbon->toDateString(),
            'player_id' => $login_log['common_prop']['player_id']
        ])->first();

        if (!empty($day_game_player_login_log)) {
            return $day_game_player_login_log;
        }

        $day_game_player_login_log = new DayGamePlayerLoginLog();
        $day_game_player_login_log->day = $carbon->toDateString();
        $day_game_player_login_log->week = $carbon->weekOfYear;
        $day_game_player_login_log->month = $carbon->month;
        $day_game_player_login_log->year = $carbon->year;
        $day_game_player_login_log->player_id = $login_log['common_prop']['player_id'];
        $day_game_player_login_log->player_name = $login_log['common_prop']['name'];
        $day_game_player_login_log->user_id = $login_log['account'];
        $day_game_player_login_log->save();

        return $day_game_player_login_log;
    }
}
