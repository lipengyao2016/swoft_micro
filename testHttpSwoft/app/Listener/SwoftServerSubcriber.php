<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 9:56
 */

namespace App\Listener;
use App\Model\Logic\ApolloLogic;
use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\EventInterface;
use Swoft\Event\EventSubscriberInterface;
use Swoft\Event\Listener\ListenerPriority;
use Swoft\Log\Helper\CLog;
use Swoft\Server\ServerEvent;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class SwoftServerSubcriber
 * @package App\Listener
 * @Subscriber()
 */
class SwoftServerSubcriber  implements EventSubscriberInterface
{

    /**
     * @Inject()
     * @var ApolloLogic
     */
    private $apolloLogic;
    /**
     * Configure events and corresponding processing methods (you can configure the priority)
     *
     * @return array
     * [
     *  'event name' => 'handler method'
     *  'event name' => ['handler method', priority]
     * ]
     */
    public static function getSubscribedEvents(): array
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            ServerEvent::BEFORE_SETTING=> 'handleSettingEvent',
            ServerEvent::BEFORE_START =>['handleStartEvent',ListenerPriority::HIGH],
        ];
    }

    public function handleSettingEvent(EventInterface $evt): void
    {
     //   $evt->setParams(['msg' => 'handle the event: setting position: TestSubscriber.handleSettingEvent()']);

        //$this->apolloLogic->pull();
        CLog::info(__METHOD__.' called');
    }

    public function handleStartEvent(EventInterface $evt): void
    {
      //  $evt->setParams(['msg' => 'handle the event: start position: TestSubscriber.handleStartEvent()']);
        CLog::info(__METHOD__.' called');
    }
}