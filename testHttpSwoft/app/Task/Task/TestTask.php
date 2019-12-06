<?php declare(strict_types=1);


namespace App\Task\Task;

use App\Model\Logic\KafkaConsumerLogic;
use Swoft\Log\Helper\Log;
use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class TestTask
 *
 * @since 2.0
 *
 * @Task(name="testTask")
 */
class TestTask
{

    /**
     * @Inject()
     * @var KafkaConsumerLogic
     */
    private $kafkaConsumerLogic;


    /**
     * @TaskMapping(name="list")
     *
     * @param int    $id
     * @param string $default
     *
     * @return array
     */
    public function getList(int $id, string $default = 'def'): array
    {
        Log::debug(__METHOD__.' id:'.$id.' current pid:'.posix_getpid().
        ' extData:'.json_encode(context()->getRequest()->getExt()));

        $this->kafkaConsumerLogic->consumer();


       return [
            'list'    => [1, 3, 3],
            'id'      => $id,
            'default' => $default
        ];




    }

    /**
     * @TaskMapping()
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        Log::debug(__METHOD__.' id:'.$id.' current pid:'.posix_getpid());
        if ($id > 10) {
            return true;
        }

        return false;
    }

    /**
     * @TaskMapping()
     *
     * @param string $name
     *
     * @return null
     */
    public function returnNull(string $name)
    {
        Log::debug(__METHOD__.' current pid:'.posix_getpid());
        return null;
    }

    /**
     * @TaskMapping()
     *
     * @param string $name
     */
    public function returnVoid(string $name): void
    {
        Log::debug(__METHOD__.' current pid:'.posix_getpid());
        return;
    }
}