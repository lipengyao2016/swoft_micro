<?php declare(strict_types=1);


namespace App\Listener;


use App\Common\NetworkUtils;
use App\Model\Logic\ApolloLogic;
use App\Model\Logic\CommonConfigBean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Co;
use Swoft\Consul\Agent;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\HttpServer;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Server\SwooleEvent;
use Swoole\Coroutine;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Pool;
use Swoft\Redis\Redis;

/**
 * Class RegisterServiceListener
 *
 * @since 2.0
 *
 * @Listener(event=SwooleEvent::START)
 */
class RegisterServiceListener implements EventHandlerInterface
{
    /**
     * @Inject()
     *
     * @var Agent
     */
    private $agent;

    /**
     * @Inject()
     *
     * @var Pool
     */
    private $redis;

    /**
     * @Inject()
     *
     * @var CommonConfigBean
     */
    private $commonConfig;


    /**
     * @Inject()
     * @var NetworkUtils
     */
    private $networkUtils;


    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        /** @var HttpServer $httpServer */
        $httpServer = $event->getTarget();
        $curServiceId = 'swoft_' . uniqid() . '_' . $this->networkUtils->get_server_ip();
        $this->commonConfig->setCurServiceID($curServiceId);
        $healthUrl = "http://".getenv('dockerip').":8050/bean/requestClass/";
        CLog::info(__METHOD__.' healthUrl:'.$healthUrl);

        $service = [
            'ID' => $curServiceId,
            'Name' => 'swoft',
            'Tags' => ['http'],
            'Address' => getenv('dockerip'),
            //'127.0.0.1',
            'Port' => 8051,
            //$httpServer->getPort(),
         /*   'Meta' => [
                'version' => '1.0'
            ],
            'EnableTagOverride' => false,
            'Weights' => [
                'Passing' => 10,
                'Warning' => 1
            ],*/

            "check" =>
                    [
                       // "Name" => "swoft-http-check",
                       // "Status" => "passing",
                        "http" => $healthUrl,
                        "interval" => "10s",
                       // "Timeout" => "10s",
                      //  "TTL" => '15s',
                        "deregisterCriticalServiceAfter"=> "1m",
                     //   "TLSSkipVerify" => true,
                    ],

        ];

        // Register
       // $this->agent->registerService($service);
        CLog::info('Swoft http register service success by consul! data:%s',
            json_encode($service));



      /*  $runTimeFile = alias(sprintf('@runtime/update_apollo.flag'));
        Log::info(__METHOD__.' runTimeFile:'.$runTimeFile);
        if(!file_exists($runTimeFile))*/
    }


}