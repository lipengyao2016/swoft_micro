<?php declare(strict_types=1);


namespace App\Listener;


use App\Model\Logic\CommonConfigBean;
use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Co;
use Swoft\Consul\Agent;
use Swoft\Consul\Exception\ClientException;
use Swoft\Consul\Exception\ServerException;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\HttpServer;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Server\SwooleEvent;
use Swoole\Coroutine;

/**
 * Class DeregisterServiceListener
 *
 * @since 2.0
 *
 * @Listener(SwooleEvent::SHUTDOWN)
 */
class DeregisterServiceListener implements EventHandlerInterface
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
     * @var CommonConfigBean
     */
    private $commonConfig;

    /**
     * @param EventInterface $event
     *
     * @throws ReflectionException
     * @throws ContainerException
     * @throws ClientException
     * @throws ServerException
     */
    public function handle(EventInterface $event): void
    {
        /** @var HttpServer $httpServer */
        $httpServer = $event->getTarget();

        $curServiceId = $this->commonConfig->getCurServiceID();
        Log::debug(__METHOD__.'curServiceId:'.$curServiceId);
      // $this->agent->deregisterService($curServiceId);
    }
}