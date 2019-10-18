<?php declare(strict_types=1);


namespace App\Process;


use App\Model\Logic\ApolloLogic;
use App\Model\Logic\MonitorLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\Log;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;

/**
 * Class MonitorProcess
 *
 * @since 2.0
 *
 * @Bean()
 */
class MonitorProcess extends UserProcess
{
    /**
     * @Inject()
     *
     * @var MonitorLogic
     */
    private $logic;

    /**
     * @Inject()
     * @var ApolloLogic
     */
    private $apolloLogic;


    /**
     * @param Process $process
     *
     * @throws DbException
     */
    public function run(Process $process): void
    {
        Log::debug(__METHOD__.' start.');
        //$this->logic->monitor($process);
        $this->apolloLogic->listen();
        Log::debug(__METHOD__.' end.');
    }
}
