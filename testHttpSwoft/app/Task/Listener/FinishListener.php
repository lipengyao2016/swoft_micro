<?php declare(strict_types=1);


namespace App\Task\Listener;

use function context;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Task\TaskEvent;

/**
 * Class FinishListener
 *
 * @since 2.0
 *
 * @Listener(event=TaskEvent::FINISH)
 */
class FinishListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        CLog::info(context()->getTaskUniqid());
        Log::debug(__METHOD__.' taskUniqId:'.context()->getTaskUniqid().
        ' data:'.json_encode(context()->getTaskData()));
    }
}