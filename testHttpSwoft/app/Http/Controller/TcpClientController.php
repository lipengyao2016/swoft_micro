<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Logic\TcpPackageLogic;
use App\Rpc\Lib\UserInterface;
use Exception;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Co;
use Swoft\Exception\SwoftException;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\Log;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;
use Swoft\Tcp\Packer\SimpleTokenPacker;

/**
 * Class TcpClientController
 *
 * @since 2.0
 *
 * @Controller()
 */
class TcpClientController
{


    /**
     * @Inject()
     * @var TcpPackageLogic
     */
    private $tcpPackageLogic;

    /**
     * @RequestMapping("getList")
     *
     * @return array
     */
    public function getList(): array
    {
        Log::debug(__METHOD__.' called:'.' pid:'.posix_getpid());
        $ret = tcpTest($this->tcpPackageLogic->encode('list','help'));
        return [$ret];
    }

    /**
     * @RequestMapping()
     * Notes:
     * User: user_1234
     * DateTime: 2019/9/27 18:42
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Apollo\Exception\ApolloException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function echo(Request $request): array
    {
        Log::debug(__METHOD__.' called:'.' pid:'.posix_getpid());
        $ret = requestTcp('tcp://127.0.0.1:18309','echo',$request->input('data','lipy'));
        return [$ret];
    }
}