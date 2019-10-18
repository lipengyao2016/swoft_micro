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
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
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
     * @return array
     */
    public function request(): array
    {
        $id = (string)Co::tid();

        /** @var RequestBean $request */
        $request = BeanFactory::getRequestBean('requestBean', $id);


        $cli = new Client(config('application.swoft_server_host','sdf'),
            config('application.swoft_server_http_port','sdf')
        );
        $cli->get('/bean/requestClass/');
        $result = $cli->body;
        $cli->close();

        Log::debug(__METHOD__.' result:'.$result);

        return ['local' => $request->getData(), 'remote' => $result ];
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
