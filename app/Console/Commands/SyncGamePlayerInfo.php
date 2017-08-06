<?php

namespace App\Console\Commands;

use App\Common\Constants;
use App\Models\GamePlayer;
use Carbon\Carbon;
use GPBMetadata\PATH\Game;
use IDCT\Networking\Ssh\Credentials;
use IDCT\Networking\Ssh\SftpClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

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
     * 同步到中心日志服务器的日志收集地址
     */
    const CENTER_SERVER_LOG_PATH = '/sdb1/game/logs/';

    /**
     * SSH认证的用户名
     */
    const SSH_AUTH_USER_NAME = 'root';

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
            $hosts = Config::get('services.game_server.outer');
        } else {
            $hosts = Config::get('services.game_server.inner');
        }

        foreach ($hosts as $host) {
            $credentials = Credentials::withPublicKey(self::SSH_AUTH_USER_NAME,
                $this->getSshKeyPath(self::SSH_PUBLIC_KEY), $this->getSshKeyPath(self::SSH_PRIVATE_KEY));

            $client->setCredentials($credentials);
            $client->connect($host);
            $client->scpDownload($this->getRemotePlayerLogName(), $this->getCenterServerPlayerLogName($host));
            $client->close();

            $this->processPlayerLog($this->getCenterServerPlayerLogName($host));
        }
    }

    /**
     * 获取当天远程游戏角色日志名
     * @return string
     */
    protected function getRemotePlayerLogName()
    {
        return self::REMOTE_GAME_LOG_PATH . self::PLAYER_FILE_NAME . '_' . Carbon::now()->toDateString();
    }

    /**
     * @param $key_name
     * @return string
     */
    protected function getSshKeyPath($key_name)
    {
        return getenv('HOME') . DIRECTORY_SEPARATOR . '.ssh/' . $key_name;
    }

    /**
     * @param $server_ip
     * @return string
     */
    protected function getCenterServerPlayerLogName($server_ip)
    {
        return self::PLAYER_FILE_NAME . '_' . $server_ip . '_' . Carbon::now()->toDateString() . '.log';
    }


    protected function processPlayerLog($file_name)
    {
        if (!file_exists($file_name)) {
            return false;
        }
        $lines = file($file_name, FILE_SKIP_EMPTY_LINES);
        if (empty($lines)) {
            return false;
        }

        $player_login_logs = [];
        foreach ($lines as $idx => $line) {
            $arr = explode(' ', $line);
            $player_log = json_decode($arr[5], true);
            if (!isset($player_log['common_prop'])) {
                continue;
            }

            $player_login_logs[] = [
                'player_id' => $player_log['common_prop']['player_id'],
                'player_name' => isset($player_log['common_prop']['name']) ? $player_log['common_prop']['name'] : '',
                'login_time' => !empty($player_log['login_time']) ? Carbon::createFromTimestamp($player_log['login_time'])->toDateTimeString() : null,
                'logout_time' => !empty($player_log['logout_time']) ? Carbon::createFromTimestamp($player_log['logout_time'])->toDateTimeString() : null,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];

            if ($idx >= Constants::BATCH_SIZE) {
                DB::table('game_player_login')->insert($player_login_logs);
                $player_login_logs = [];
            }

            // 查询是否已经存在角色
            $game_player = GamePlayer::where('player_id', $player_log['common_prop']['player_id'])->first();
            if (!empty($game_player)) {
                continue;
            }
            $game_player = new GamePlayer();
            $game_player->player_id = $player_log['common_prop']['player_id'];
            $game_player->player_name = isset($player_log['common_prop']['name']) ? $player_log['common_prop']['name'] : '';
            $game_player->user_name = isset($player_log['account']) ? $player_log['account'] : '';
            $game_player->server_id = $player_log['server_id'];
            $game_player->save();
        }

        DB::table('game_player_login')->insert($player_login_logs);
    }
}