<?php declare(strict_types=1);

namespace App\Tcp\Controller;

use Swoft\Log\Helper\Log;
use Swoft\Tcp\Server\Annotation\Mapping\TcpController;
use Swoft\Tcp\Server\Annotation\Mapping\TcpMapping;
use Swoft\Tcp\Server\Request;
use Swoft\Tcp\Server\Response;
use function strrev;

/**
 * Class DemoController
 *
 * @TcpController()
 */
class DemoController
{
    /**
     * @TcpMapping("list", root=true)
     * @param Response $response
     */
    public function list(Request $request,Response $response): void
    {
        $str = $request->getPackage()->getDataString();
        Log::debug(__METHOD__.' req data:'.$str.' fd:'.$request->getFd());
        $response->setData('[list]allow command: list, echo, demo.echo');
    }

    /**
     * @TcpMapping("echo")
     * @param Request  $request
     * @param Response $response
     */
    public function index(Request $request, Response $response): void
    {

        $str = $request->getPackage()->getDataString();
        Log::debug(__METHOD__.' req data:'.$str.' fd:'.$request->getFd());
        $response->setData('[demo.echo]hi, we received your message: ' . $str);
    }

    /**
     * @TcpMapping("strrev", root=true)
     * @param Request  $request
     * @param Response $response
     */
    public function strRev(Request $request, Response $response): void
    {
        $str = $request->getPackage()->getDataString();
        Log::debug(__METHOD__.' req data:'.$str.' fd:'.$request->getFd());

        $response->setData(strrev($str));
    }

    /**
     * @TcpMapping("echo", root=true)
     * @param Request  $request
     * @param Response $response
     */
    public function echo(Request $request, Response $response): void
    {
        // $str = $request->getRawData();
        $str = $request->getPackage()->getDataString();
        Log::debug(__METHOD__.' req data:'.$str.' fd:'.$request->getFd());
        $response->setData('[echo]hi, we received your message: ' . $str);
    }
}
