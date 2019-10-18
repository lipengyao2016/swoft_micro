<?php declare(strict_types=1);


namespace App\Listener\Test;


use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Server\ServerEvent;

/**
 * Class TaskProcessListener
 *
 * @since 2.0
 *
 * @Listener(ServerEvent::TASK_PROCESS_START)
 */
class TaskProcessListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        $context = context();

        Log::info('Task worker start'
            . ' ,target:'.get_class($event->getTarget()) . ' params:'.json_encode($event->getParams())
        .' task id:'.$context->getWorkerId());
    }
}