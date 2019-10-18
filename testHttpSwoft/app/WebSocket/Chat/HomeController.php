<?php declare(strict_types=1);

namespace App\WebSocket\Chat;

use Swoft\Log\Helper\Log;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use Swoft\WebSocket\Server\Message\Request;

/**
 * Class HomeController
 *
 * @WsController()
 */
class HomeController
{
    /**
     * Message command is: 'home.index'
     *
     * @return void
     * @MessageMapping()
     */
    public function index(Request $req): void
    {
        Log::debug(__METHOD__.' .fd:%d.data:%s',$req->getFd(),$req->getMessage()->toString());
        Session::mustGet()->push('hi, this is home.index');
    }

    /**
     * Message command is: 'home.echo'
     *
     * @param string $data
     * @MessageMapping()
     */
    public function echo(Request $req,$data): void
    {
        Log::debug(__METHOD__.' .fd:%d.message data:%s,data:%s',$req->getFd(),$req->getMessage()->toString()
        ,$data);
        Session::mustGet()->push('(home.echo)Recv: ' . $data);
    }

    /**
     * Message command is: 'home.ar'
     *
     * @param string $data
     * @MessageMapping("ar")
     *
     * @return string
     */
    public function autoReply(Request $req,$data): void
    {
        Log::debug(__METHOD__.' .fd:%d.message data:%s,data:%s',$req->getFd(),$req->getMessage()->toString()
            ,$data);
        Session::mustGet()->push( '(home.ar)Recv: ' . $data);
    }
}
