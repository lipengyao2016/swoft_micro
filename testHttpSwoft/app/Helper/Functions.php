<?php

use Swoft\Tcp\Protocol;
use Swoole\Coroutine\Client;

/**
 * Custom global functions
 */

function user_func(): string
{
    return 'hello';
}

const RPC_EOL = "\r\n\r\n";

function request($host, $class, $method, $param, $version = '1.0', $ext = []) {
    $fp = stream_socket_client($host, $errno, $errstr);
    if (!$fp) {
        throw new Exception("stream_socket_client fail errno={$errno} errstr={$errstr}");
    }

    $req = [
        "jsonrpc" => '2.0',
        "method" => sprintf("%s::%s::%s", $version, $class, $method),
        'params' => $param,
        'id' => '',
        'ext' => $ext,
    ];
    $data = json_encode($req) . RPC_EOL;

    \Swoft\Log\Helper\Log::debug(__METHOD__.' req data:'.$data);

    fwrite($fp, $data);

    $result = '';
    while (!feof($fp)) {
        $tmp = stream_socket_recvfrom($fp, 1024);

        if ($pos = strpos($tmp, RPC_EOL)) {
            $result .= substr($tmp, 0, $pos);
            break;
        } else {
            $result .= $tmp;
        }
    }

    fclose($fp);

    \Swoft\Log\Helper\Log::debug(__METHOD__.' result data:'.$result);

    return json_decode($result, true);
}

 function tcpTest($msg)
{
    \Swoft\Log\Helper\Log::debug(__METHOD__.'  msg:'.$msg);
    $proto = new Protocol();

    // If your tcp server use length check.
    // $proto->setOpenLengthCheck(true);

    var_dump($proto->getConfig());

    $host = '127.0.0.1';
    $port = 18309;

    $client = new Client(SWOOLE_SOCK_TCP);
    // Notice: config client
    $client->set($proto->getConfig());

    if (!$client->connect((string)$host, (int)$port, 5.0)) {
        $code = $client->errCode;
        /** @noinspection PhpComposerExtensionStubsInspection */
        $msg = socket_strerror($code);
        //$output->error("Connect server failed. Error($code): $msg");
        return ['ret' => "Connect server failed. Error($code): $msg"];
    }

    // Send message $msg . $proto->getPackageEOf()
    if (false === $client->send($proto->packBody($msg))) {
        /** @noinspection PhpComposerExtensionStubsInspection */
        //$output->error();
        return ['ret' => 'Send error - ' . socket_strerror($client->errCode)];
    }

    // Recv response
    $res = $client->recv(2.0);
    if ($res === false) {
        /** @noinspection PhpComposerExtensionStubsInspection */
       // $output->error('Recv error - ' . socket_strerror($client->errCode));
        return ['ret' => 'Recv error - ' . socket_strerror($client->errCode)];
    }

    if ($res === '') {
     //   $output->info('Server closed connection');
        return ['ret' => 'Server closed connection - '];
    }

    // unpack response data
    [$head, $body] = $proto->unpackData($res);
    \Swoft\Log\Helper\Log::debug(__METHOD__.'  head:'.json_encode($head).
    ' body;'.json_encode($body));
    return ['ret' => 'ok','data'=>[
        'head' => $head,
        'body' => $body
    ]];
  //  $output->prettyJSON($head);
 //   $output->writef('<yellow>server</yellow>> %s', $body);
}

const PKG_EOF = "\r\n\r\n";

function requestTcp(string $host, string $cmd, $data, $ext = []) {
    $fp = stream_socket_client($host, $errno, $errstr);
    if (!$fp) {
        throw new Exception("stream_socket_client fail errno={$errno} errstr={$errstr}");
    }

    $req = [
        'cmd'  => $cmd,
        'data' => $data,
        'ext' => $ext,
    ];
    $data = json_encode($req) . PKG_EOF;
    \Swoft\Log\Helper\Log::debug(__METHOD__.' data:'.$data);
    fwrite($fp, $data);

    $result = '';
    while (!feof($fp)) {
        $tmp = stream_socket_recvfrom($fp, 1024);

        if ($pos = strpos($tmp, PKG_EOF)) {
            $result .= substr($tmp, 0, $pos);
            break;
        } else {
            $result .= $tmp;
        }
    }

    \Swoft\Log\Helper\Log::debug(__METHOD__.' result:'.$result);

    fclose($fp);
    return json_decode($result, true);
}