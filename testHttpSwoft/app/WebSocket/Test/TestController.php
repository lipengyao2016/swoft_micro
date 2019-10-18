<?php declare(strict_types=1);

namespace App\WebSocket\Test;

use Swoft\Log\Helper\Log;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use Swoft\WebSocket\Server\Message\Message;
use Swoft\WebSocket\Server\Message\Request;
use Swoft\WebSocket\Server\Message\Response;

/**
 * Class HomeController
 *
 * @WsController()
 */
class TestController
{
    /**
     * Message command is: 'test.index'
     *
     * @return void
     * @MessageMapping()
     */
    public function index(Message $msg): void
    {
        $data = $msg->getData();
        $conn = Session::mustGet();
        $fd = \is_numeric($data) ? (int)$data : $conn->getFd();
        Log::debug(__METHOD__.' called! .data:%s,fd:%d',$data,$fd);

        Session::mustGet()->push('hi, this is test.index');
    }

    /**
     * Message command is: 'test.index'
     * @param Message $msg
     *
     * @return void
     * @MessageMapping("close")
     */
    public function close(Message $msg): void
    {
        $data = $msg->getData();
        $conn = Session::mustGet();
        $fd = \is_numeric($data) ? (int)$data : $conn->getFd();
        Log::debug(__METHOD__.' called! .data:%s,fd:%d',$data,$fd);

        $conn->push("hi, will close conn $fd");
        // disconnect
        \server()->disconnect($fd);
    }

    /**
     * Message command is: 'test.req'
     *
     * @param Request $req
     *
     * @return void
     * @MessageMapping("req")
     */
    public function injectRequest(Request $req): void
    {
        $data =  $req->getMessage();
        $fd = $req->getFd();
        Log::debug(__METHOD__.' called! .data:%s,fd:%d,frame data:%s'
            ,$data->toString(),$fd,$req->getFrame()->data);
        Session::mustGet()->push("(your FD: $fd)message data: " . \json_encode($req->getMessage()->toArray()));
    }

    /**
     * Message command is: 'test.msg'
     *
     * @param Message $msg
     *
     * @return void
     * @MessageMapping("msg")
     */
    public function injectMessage(Message $msg): void
    {
        Log::debug(__METHOD__.' called! .data:%s', json_encode($msg->toArray()) );
        Session::mustGet()->push('message data: ' . \json_encode($msg->toArray()));
    }

    /**
     * Message command is: 'echo'
     *
     * @param $data
     * @MessageMapping(root=true)
     */
    public function echo($data): void
    {
        Log::debug(__METHOD__.' called! .data:%s',$data);
        Session::mustGet()->push('(echo)Recv: ' . $data);
    }

    /**
     * Message command is: 'echo'
     *
     * @param Request  $req
     * @param Response $res
     * @MessageMapping(root=true)
     */
    public function hi(Request $req, Response $res): void
    {
        $fd  = $req->getFd();
        $ufd = (int)$req->getMessage()->getData();

        Log::debug(__METHOD__.' called! .data:%s,fd:%d,frame data:%s'
            ,$req->getMessage()->toString(),$fd,$req->getFrame()->data);

        if ($ufd < 1) {
            Session::mustGet()->push('data must be an integer');
            return;
        }

        $res->setFd($ufd)->setContent("Hi #{$ufd}, I am #{$fd}");
    }

    /**
     * Message command is: 'bin'
     *
     * @param $data
     * @MessageMapping("bin", root=true)
     */
    public function binary($data): void
    {
        Log::debug(__METHOD__.' called! .data:'.$data);
        Session::mustGet()->push('Binary: ' . $data, \WEBSOCKET_OPCODE_BINARY);
    }

    /**
     * Message command is: 'ping'
     *
     * @MessageMapping("ping", root=true)
     */
    public function pong(): void
    {
        Log::debug(__METHOD__.' called! .');
        Session::mustGet()->push('pong!', \WEBSOCKET_OPCODE_PONG);
    }

    /**
     * Message command is: 'test.ar'
     *
     * @param $data
     * @MessageMapping("ar")
     *
     * @return string
     */
    public function autoReply($data): string
    {
        Log::debug(__METHOD__.' called! .data:%s',$data);
        Session::mustGet()->push('(home.ar)Recv: ' . $data);
    }
}
