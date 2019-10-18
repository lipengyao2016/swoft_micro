<?php declare(strict_types=1);


namespace App\Listener\Test;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Server\ServerEvent;

/**
 * Class WorkerStartListener
 *
 * @since 2.0
 *
 * @Listener(ServerEvent::WORK_PROCESS_START)
 */
class WorkerStartListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        $context = context();

        Log::info('Worker Start context=' . get_class($context)
            . ' ,target:'.get_class($event->getTarget()) . ' params:'.json_encode($event->getParams()));
    }
}