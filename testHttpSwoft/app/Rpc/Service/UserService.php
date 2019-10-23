<?php declare(strict_types=1);


namespace App\Rpc\Service;


use App\Model\Logic\CommonConfigBean;
use App\Rpc\Lib\UserInterface;
use Exception;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Co;
use Swoft\Log\Helper\Log;
use Swoft\Rpc\Server\Annotation\Mapping\Service;

/**
 * Class UserService
 *
 * @since 2.0
 *
 * @Service()
 */
class UserService implements UserInterface
{
    /**
     * @Inject()
     * @var CommonConfigBean
     */
    private $commonConfig;
    /**
     * @param int   $id
     * @param mixed $type
     * @param int   $count
     *
     * @return array
     */
    public function getList(int $id, $type, int $count = 10): array
    {
        Log::debug(__METHOD__.' id:'.$id.' type:'.$type.' pid:'.posix_getpid().
        ' serviceId:'.$this->commonConfig->getCurServiceID());
        return ['name' => ['remote list V2.0...']];
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        Log::debug(__METHOD__.' id:'.$id);
        return false;
    }

    /**
     * @return void
     */
    public function returnNull(): void
    {
        Log::debug(__METHOD__.' called!');
        return;
    }

    /**
     * @return string
     */
    public function getBigContent(): string
    {
        $content = Co::readFile(__DIR__ . '/big.data');
        Log::debug(__METHOD__.' bigCotent len:'.strlen($content));
        return $content;
    }

    /**
     * Exception
     * @throws Exception
     */
    public function exception(): void
    {
        Log::debug(__METHOD__.' called!');
        throw new Exception('exception version');
    }

    /**
     * @param string $content
     *
     * @return int
     */
    public function sendBigContent(string $content): int
    {
        Log::debug(__METHOD__.' content len:'.strlen($content));
        return strlen($content);
    }
}