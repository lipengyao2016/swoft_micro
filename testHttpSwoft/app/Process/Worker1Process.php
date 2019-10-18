<?php declare(strict_types=1);


namespace App\Process;


use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Process\Annotation\Mapping\Process;
use Swoft\Process\Contract\ProcessInterface;
use Swoole\Coroutine;
use Swoole\Process\Pool;

/**
 * Class Worker1Process
 *
 * @since 2.0
 *
 * @Process(workerId=0)
 */
class Worker1Process implements ProcessInterface
{
    /**
     * @param Pool $pool
     * @param int  $workerId
     */
    public function run(Pool $pool, int $workerId): void
    {
        while (true) {
            Log::info('worker-' . $workerId.' pid:'.posix_getpid().' workerId:'.$workerId);

            Coroutine::sleep(5);
        }
    }
}
