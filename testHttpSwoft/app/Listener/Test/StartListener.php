<?php declare(strict_types=1);


namespace App\Listener\Test;


use App\Model\Logic\ApolloLogic;
use Swoft\Co;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Server\SwooleEvent;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class StartListener
 *
 * @since 2.0
 *
 * @Listener(event=SwooleEvent::START)
 */
class StartListener implements EventHandlerInterface
{
    /**
     * @Inject()
     * @var ApolloLogic
     */
    private $apolloLogic;

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        $context = context();

        Log::info('Start context=' . get_class($context).
        ' ,target:'.get_class($event->getTarget()) . ' params:'.json_encode($event->getParams()));

     /*   $apolloFlagFile = sprintf('@runtime/apollo_update.flag');
        unlink(alias($apolloFlagFile));
        Log::info(' unlink $apolloFlagFile:' . alias($apolloFlagFile));*/

      /*  $dbName = config('application.db_dsn','ds');
        Log::info(__METHOD__.' begin dbName:'.json_encode($dbName));

        {
            Log::info(__METHOD__.' runTimeFile not exist,update config from apollo.');
            $apolloConfigData = $this->apolloLogic->pull();
            // $config = \Swoft::getBean('config');
            $this->updateConfigFile($apolloConfigData);
            $runFileData = 'apollo config has update time:'.date('Y-m-d h:i:s', time());
            Log::info(__METHOD__.' runFileData:'.$runFileData);
            // Co::writeFile($runTimeFile, $runFileData, FILE_NO_DEFAULT_CONTEXT);
        }

        /*  else
          {
              Log::info(__METHOD__.' runTimeFile  exist,break.');
              unlink($runTimeFile);
          }*/
        /*$dbName = config('application.db_dsn','aa');
        Log::info(__METHOD__.' 22  dbName:'.json_encode($dbName));*/
    }

    public function updateConfigFile(array $data): void
    {
        foreach ($data as $namespace => $namespaceData) {
            $configFile = sprintf('@config/%s.php', $namespace);

            $configKVs = $namespaceData['configurations'] ?? '';
            $content   = '<?php return ' . var_export($configKVs, true) . ';';
            Log::info('Apollo content:'.$content);

            Co::writeFile(alias($configFile), $content, FILE_NO_DEFAULT_CONTEXT);
            Log::info('Apollo update success！');
        }

        /** @var HttpServer $server */
        $server = bean('httpServer');
        Log::info(' server:'.get_class($server));
        $server->restart();
        Log::info('Apollo restart ok！');
    }
}