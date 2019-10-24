<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use App\Model\Logic\RequestBean;
use App\Model\Logic\RequestBeanTwo;
use Co\Http\Client;
//use GuzzleHttp\Client;
//use GuzzleHttp\HandlerStack;
use Swoft\Bean\BeanFactory;
use Swoft\Co;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Stdlib\Helper\ArrayHelper;
//use Yurun\Util\Swoole\Guzzle\SwooleHandler;


/**
 * Class BeanController
 *
 * @since 2.0
 *
 * @Controller(prefix="bean")
 */
class BeanController
{
    /**
     * @RequestMapping()
     *
     * @param Request $request
     * @return array
     */
    public function request(Request $request): array
    {
        $id = (string)Co::tid();

        /** @var RequestBean $request */
        $requestBean = BeanFactory::getRequestBean('requestBean', $id);

        $headers = $request->getHeaders();
        CLog::info(__METHOD__.' headers:'.json_encode($headers));

        $incoming_headers = array(
            'x-request-id',
            'x-b3-traceid',
            'x-b3-spanid',
            'x-b3-parentspanid',
            'x-b3-sampled',
            'x-b3-flags',
            'x-ot-span-context',
          //  'connection'
        );
        $spanHeaders = ArrayHelper::filter($headers,$incoming_headers);
        $spanHeaders['client_id'] = 'lipy';


        $result = '';
        $cli = new Client(config('application.swoft_server_host','sdf'),
            config('application.swoft_server_http_port','sdf')
        );

        //$cli = new Client('192.168.5.61', 8050);
 /*       $cli->setHeaders(
            [
                'x-b3-traceid'=>['ququ'],
                'Host' => ["192.168.5.61:8050"],
                'Accept-Encoding' => ['gzip'],
                "connection"=>["keep-alive"]
            ]
        );*/

        $clientHeaders = [   'Host' => "localhost",
            "User-Agent" => 'Chrome/49.0.2587.3',
            'Accept' => 'text/html,application/xhtml+xml,application/xml',
            'Accept-Encoding' => 'gzip'];


        $clientHeaders = ArrayHelper::merge($clientHeaders,$spanHeaders);
        CLog::info(__METHOD__.' clientHeaders:'.json_encode($clientHeaders));
        $cli->setHeaders($clientHeaders);

        $cli->get('/bean/requestClass/');
        $result = $cli->body;
        $cli->close();

     /*   sgo(function() use ($spanHeaders,$result){
            // todo
            $testUrl = 'http://192.168.5.61:8050/bean/requestClass/';
            $handler = new SwooleHandler();
            $stack = HandlerStack::create($handler);
            $client = new Client(['handler' => $stack]);
            $response = $client->request('GET', $testUrl,['headers' => $spanHeaders]);
            $result = $response->getBody();
            $statusCode = $response->getStatusCode();
            CLog::info(__METHOD__.' internel execute ok result:'.json_encode($result));
        });*/
        CLog::info(__METHOD__.' result:'.json_encode($result));

        return ['local' => $requestBean->getData(), 'remote' => $result ];
    }

    /**
     * @return array
     *
     * @RequestMapping()
     */
    public function requestClass(): array
    {
        $id = (string)Co::tid();

        /* @var RequestBeanTwo $request */
        $request = BeanFactory::getRequestBean(RequestBeanTwo::class, $id);
        return $request->getData();
    }
}
