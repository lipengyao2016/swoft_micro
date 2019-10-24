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
use Swoft\Bean\BeanFactory;
use Swoft\Co;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;


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
       /*  foreach ($headers as $name => $values) {
             CLog::info(__METHOD__.' name:'.json_encode($name).' value:'.json_encode($values));
         }*/

        $cli = new Client(config('application.swoft_server_host','sdf'),
            config('application.swoft_server_http_port','sdf')
        );

        $cli->setHeaders($headers);

        $cli->get('/bean/requestClass/');
        $result = $cli->body;
        $cli->close();

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
