<?php declare(strict_types=1);


namespace App\Console\Command;

use ReflectionException;
use Swoft\Apollo\Config;
use Swoft\Apollo\Exception\ApolloException;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Co;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Http\Server\HttpServer;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\WebSocket\Server\WebSocketServer;
use Swoole\Coroutine;
use Throwable;

/**
 * Class AgentCommand
 *
 * @since 2.0
 *
 * @Command("agent")
 */
class AgentCommand
{
    /**
     * @Inject()
     *
     * @var Config
     */
    private $config;

    /**
     * @CommandMapping(name="index")
     */
    public function index(): void
    {
        $namespaces = [
            'application'
        ];

        CLog::info('Apollo index start!! ');
        $retData = $this->config->batchPull($namespaces);
        CLog::info('  retData:%s',json_encode($retData));
        $this->updateConfigFile($retData);
        CLog::info('  update finished');

        //$this->saveUpdateFlag();

       /*while (true) {
            try {
                $this->config->listen($namespaces, [$this, 'updateListenCfgFile']);
            } catch (Throwable $e) {
                CLog::error('Config agent fail(%s %s %d)!', $e->getMessage(), $e->getFile(), $e->getLine());
            }

        }*/
    }

    public function updateListenCfgFile(array $data): void
    {
        CLog::info('  updateListenCfgFile data:'.json_encode($data));
    }

    public function saveUpdateFlag():void
    {
        $apolloFlagFile = sprintf('@runtime/apollo_update.flag');
        $content   = 'apollo first config update time:'.date('Y-m-d h:i:s', time());
        Co::writeFile(alias($apolloFlagFile), $content, FILE_NO_DEFAULT_CONTEXT);
        CLog::info('Apollo update apolloFlagFile:'.alias($apolloFlagFile).' content:'.$content);
    }

    /**
     * @param array $data
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function updateConfigFile(array $data): void
    {
        foreach ($data as $namespace => $namespaceData) {
            $configFile = sprintf('@config/%s.php', $namespace);

            $configKVs = $namespaceData['configurations'] ?? '';
            $content   = '<?php return ' . var_export($configKVs, true) . ';';
            Co::writeFile(alias($configFile), $content, FILE_NO_DEFAULT_CONTEXT);

            CLog::info('Apollo update namespace: '.$namespace);

//            /** @var ServiceServer $server */
//            $server = bean('rpcServer');
//            $server->restart();

            /** @var WebSocketServer $server */
//            $server = bean('wsServer');
//            $server->restart();
        }

        CLog::info('Apollo update finished!! ');

        /** @var HttpServer $server */
        $server = bean('httpServer');
        if($server->isRunning())
        {
            CLog::info('Apollo http server is running,restart!! ');
            //$server->restart();
        }
        else
        {
            CLog::info('Apollo http server has not running,start!! ');
            // Exe restart shell
            //$coRet = Coroutine::exec('/usr/local/bin/php /var/www/swoft/bin/swoft http:start > /var/www/swoft/runtime/system.log  2>&1');
            //CLog::info('Apollo http server coRet: '.json_encode($coRet));

         /*   try
            {
                $server->stop();
                CLog::info('Apollo http server stop end. ');
            }
            catch (\Exception $e)
            {
                CLog::info('Apollo http server error. ');
            }*/
            CLog::info('Apollo http server exit.');
           // exit(0);

        }



    }
}