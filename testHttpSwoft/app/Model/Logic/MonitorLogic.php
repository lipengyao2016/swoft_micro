<?php declare(strict_types=1);


namespace App\Model\Logic;

use App\Model\Entity\User;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Process\Process;
use Swoft\Redis\Redis;
use Swoole\Coroutine;

/**
 * Class MonitorProcessLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class MonitorLogic
{
    /**
     * @param Process $process
     *
     * @throws DbException
     */
    public function monitor(Process $process): void
    {
        $process->name('swoft-monitor');

        while (true) {
            $connections = context()->getServer()->getSwooleServer()->connections;
            CLog::info('monitor = ' . json_encode($connections));
            Log::info('monitor = ' . json_encode($connections).' pid:'.posix_getpid());

            // Database
            $user = User::find(1)->toArray();
            CLog::info('user='.json_encode($user));
            Log::info('user='.json_encode($user));


            // Redis
            Redis::set('test', 'ok');
            CLog::info('test='.Redis::get('test'));
            Log::info('test='.Redis::get('test'));


            Coroutine::sleep(5);
        }
    }
}