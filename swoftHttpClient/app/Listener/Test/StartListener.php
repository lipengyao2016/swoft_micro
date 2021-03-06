<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Listener\Test;

use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Exception\SwoftException;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Server\SwooleEvent;

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
     * @param EventInterface $event
     *
     * @throws SwoftException
     */
    public function handle(EventInterface $event): void
    {
        $context = context();
        CLog::debug('Start context=' . get_class($context));

//        $apolloFlagFile = sprintf('@runtime/apollo_update.flag');
//        unlink(alias($apolloFlagFile));
//        Log::info(' unlink $apolloFlagFile:' . alias($apolloFlagFile));

    }
}
