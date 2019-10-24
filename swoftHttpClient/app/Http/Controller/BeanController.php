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

        $incoming_headers = [ 'x-request-id',
            'x-b3-traceid',
            'x-b3-spanid',
            'x-b3-parentspanid',
            'x-b3-sampled',
            'x-b3-flags',
            'x-ot-span-context',
          //  'connection'
        ];
        $spanHeaders = ArrayHelper::filter($headers,$incoming_headers);
        //$spanHeaders['Accept'] = 'application/json';
        CLog::info(__METHOD__.' spanHeaders:'.json_encode($spanHeaders));

        $result = '';
  /*      $cli = new Client(config('application.swoft_server_host','sdf'),
            config('application.swoft_server_http_port','sdf')
        );*/

        $cli = new Client('192.168.5.61', 8050);
        $cli->headers = $spanHeaders;
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
