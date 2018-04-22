<?php

namespace App\Console\Commands;

use App\Models\DayRounds;
use Carbon\Carbon;
use Illuminate\Console\Command;
use IDCT\Networking\Ssh\Credentials;
use IDCT\Networking\Ssh\SftpClient;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class StatDayRounds extends Command
{

    /**
     * 游戏日志目录
     */
    const REMOTE_GAME_LOG_PATH = '/export/game/workdir/logs/';

    /**
     * 统计日志文件名
     */
    const LOG_FILE_NAME = 'log';

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
     * Public Key name
     */
    const SSH_PUBLIC_KEY = 'id_rsa.pub';

    /**
     * Private Key name
     */
    const SSH_PRIVATE_KEY = 'id_rsa';

    /**
     * 获取总局数shell命令
     */
    const CMD_GET_ROUNDS = 'grep 整局结算 %s | wc -l';

    /**
     * 获取16桌局数shell命令
     */
    const CMD_GET_SIXTEEN_ROUNDS = 'grep 整局结算 %s | grep "房间局数:16" | wc -l';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:day_rounds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日局数统计';

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
        $client = new SftpClient();

        $servers = Config::get('services.game_servers');

        foreach ($servers as $server) {
            $credentials = Credentials::withPublicKey(self::SSH_AUTH_USER_NAME,
                $this->getSshKeyPath(self::SSH_PUBLIC_KEY), $this->getSshKeyPath(self::SSH_PRIVATE_KEY));

            // $credentials = Credentials::withPassword($server['user'], $server['password']);
            $client->setCredentials($credentials);
            $client->connect($server['host']);

            $center_server_log_path = $this->getCenterServerLogName($server['host']);
            $client->scpDownload($this->getRemoteLogName($server['path']), $center_server_log_path);

            $client->close();

            $this->processLog($center_server_log_path, $server);
        }
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
    protected function getCenterServerLogName($server_ip)
    {
        return self::CENTER_SERVER_LOG_PATH . self::LOG_FILE_NAME . '_' . $server_ip . '_' . Carbon::yesterday()->toDateString() . '.log';
    }

    /**
     * 获取当天远程游戏角色日志名
     * @param $path
     * @return string
     */
    protected function getRemoteLogName($path = self::REMOTE_GAME_LOG_PATH)
    {
        return $path . self::LOG_FILE_NAME . '_' . Carbon::yesterday()->toDateString();
    }


    protected function processLog($file_name, $server)
    {
        if (!file_exists($file_name)) {
            return false;
        }

        $y = Carbon::yesterday();

        $rounds = intval(shell_exec(sprintf(self::CMD_GET_ROUNDS, $file_name)));
        $sixteen_rounds = intval(shell_exec(sprintf(self::CMD_GET_SIXTEEN_ROUNDS, $file_name)));

        $day_rounds = new DayRounds();
        $day_rounds->day = $y->toDateString();
        $day_rounds->week = $y->weekOfYear;
        $day_rounds->month = $y->month;
        $day_rounds->game_server_id = $server['game_server_id'];
        $day_rounds->year = $y->year;
        $day_rounds->total_rounds = $rounds;
        $day_rounds->sixteen_rounds = $sixteen_rounds;
        $day_rounds->eight_rounds = $rounds - $sixteen_rounds;
        $day_rounds->save();
        return $day_rounds;
    }

}
