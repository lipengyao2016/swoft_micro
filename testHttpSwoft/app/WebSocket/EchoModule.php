<?php declare(strict_types=1);

namespace App\WebSocket;

use Swoft\Http\Message\Request;
use Swoft\Log\Helper\Log;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\OnMessage;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use function server;

/**
 * Class EchoModule
 *
 * @WsModule("echo")
 */
class EchoModule
{
    /**
     * @OnOpen()
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Request $request, int $fd): void
    {
        Log::debug(__METHOD__.' .fd:%d.',$fd);
        Session::mustGet()->push("Opened, welcome #{$fd}!");
    }

    /**
     * @OnMessage()
     * @param Server $server
     * @param Frame  $frame
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        Log::debug(__METHOD__.' .fd:%d.data:%s',$frame->fd,$frame->data);
        $server->push($frame->fd, 'Recv: ' . $frame->data);
    }
}
