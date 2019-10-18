<?php declare(strict_types=1);

namespace App\WebSocket;

use App\WebSocket\Chat\HomeController;
use Swoft\Http\Message\Request;
use Swoft\Log\Helper\Log;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\WebSocket\Server\MessageParser\JsonParser;
use function server;

/**
 * Class ChatModule
 *
 * @WsModule(
 *     "/chat",
 *     messageParser=JsonParser::class,
 *     controllers={HomeController::class}
 * )
 */
class ChatModule
{
    /**
     * @OnOpen()
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Request $request, int $fd): void
    {
        Log::debug(__METHOD__.' .fd:%d.',$fd);
        server()->push($request->getFd(), "Opened, welcome!(FD: $fd)");
    }
}
