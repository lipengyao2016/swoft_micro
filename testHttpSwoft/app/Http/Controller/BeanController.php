<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Logic\ApolloLogic;
use App\Model\Logic\RequestBean;
use App\Model\Logic\RequestBeanTwo;
use App\service\ISmsInterface;
use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
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
     * @Inject()
     * @var ISmsInterface
     */
    protected $smsInterface;

    /**  @Inject()
     * @var ApolloLogic
     */
    protected $apolloLogic;

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     * @throws \Swoft\Exception\SwoftException
     */
    public function request(): array
    {
        $id = (string)Co::tid();

        $context = context();

        $dbName = config(null,'null');
        CLog::info(__METHOD__.' dbName:'.json_encode($dbName));

        $db = \Swoft::getBean('db');
        CLog::info(__METHOD__.' dsn:'.$db->getDsn().' userName:'
            .$db->getUsername().' password:'.$db->getPassword());

        /** @var RequestBean $request*/
        $request = BeanFactory::getRequestBean('requestBean', $id);
        CLog::debug(__METHOD__.' TID:'.$id.' req:'.get_class($request).
        //' swoole process id:'.$context->getSwooleServer()->worker_pid.
        ' cur process id:'.posix_getpid());

        $request->setData('sms'.$id);

        $this->smsInterface->send("hello,worldd");

//        $server = bean('httpServer');
//        $server->restart();

        return $request->getData();
    }

    /**
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     *
     * @RequestMapping()
     */
    public function requestClass(Request $request): array
    {
        $id = (string)Co::tid();

        $headers = $request->getHeaders();
        CLog::info(__METHOD__.' headers:'.json_encode($headers));

        /* @var RequestBeanTwo $request */
        $request = BeanFactory::getRequestBean(RequestBeanTwo::class, $id);
        Log::debug(__METHOD__.' TID:'.$id.' cls:'.get_class($request));
        return $request->getData();
    }
}