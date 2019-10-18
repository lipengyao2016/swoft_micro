<?php declare(strict_types=1);


namespace App\Task\Task;

use Swoft\Log\Helper\Log;
use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;

/**
 * Class SyncTask
 *
 * @since 2.0
 *
 * @Task(name="sync")
 */
class SyncTask
{
    /**
     * @TaskMapping()
     *
     * @param string $name
     *
     * @return string
     */
    public function test(string $name): string
    {
        Log::debug(__METHOD__.' begin current pid:'.posix_getpid());
        usleep(200);
        Log::debug(__METHOD__.'end current pid:'.posix_getpid());
        return 'sync-test-' . $name;
    }

    /**
     * @TaskMapping()
     *
     * @return bool
     */
    public function testBool(): bool
    {
        Log::debug(__METHOD__.' begin current pid:'.posix_getpid());
        usleep(200);
        Log::debug(__METHOD__.'end current pid:'.posix_getpid());
        return true;
    }

    /**
     * @TaskMapping()
     *
     * @return bool
     */
    public function testNull()
    {
        Log::debug(__METHOD__.' begin current pid:'.posix_getpid());
        usleep(200);
        Log::debug(__METHOD__.'end current pid:'.posix_getpid());
        return null;
    }
}