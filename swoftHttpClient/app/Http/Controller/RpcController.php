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

use App\Rpc\Lib\UserInterface;
use Exception;
use Swoft\Co;
use Swoft\Exception\SwoftException;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\Log;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;
use Swoft\Breaker\Annotation\Mapping\Breaker;
use Swoft\Limiter\Annotation\Mapping\RateLimiter;

/**
 * Class RpcController
 *
 * @since 2.0
 *
 * @Controller()
 */
class RpcController
{
    /**
     * @Reference(pool="user.pool")
     *
     * @var UserInterface
     */
    private $userService;

    /**
     * @Reference(pool="user.pool", version="1.2")
     *
     * @var UserInterface
     */
    private $userService2;

    /**
     * @RequestMapping("getList")
     *@Breaker(fallback="funcFallback")
     * @return array
     */
    public function getList(): array
    {
        $result  = $this->userService->getList(12, 'type');
        //$result2 = $this->userService2->getList(12, 'type');
        Log::debug(__METHOD__.' result:'.json_encode($result));
        return           $result;
       // return [$result, $result2];
    }

    public function funcFallback(): array
    {
        return ['rpc client breaker' => 'funcFallback'];
    }

    /**
     * @RequestMapping("getListLimit")
     * @RateLimiter(key="request.getUriPath()",rate=3, fallback="limiterFallback")
     * @param Request $request
     * @return array
     */
    public function getListLimit(Request $request): array
    {
        $headers = $request->header();

        $result  = $this->userService->getList(12, 'type');
        //$result2 = $this->userService2->getList(12, 'type');
        Log::debug(__METHOD__.' result:'.json_encode($result));
        return           $result;
    }

    public function limiterFallback(Request $request): array
    {
        $uri = $request->getUriPath();
        return ['rpc client limiterFallback' => $uri];
    }

    /**
     * @RequestMapping("returnBool")
     *
     * @return array
     */
    public function returnBool(): array
    {
        $result = $this->userService->delete(12);

        if (is_bool($result)) {
            return ['bool'];
        }

        return ['notBool'];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function bigString(): array
    {
        $string = $this->userService->getBigContent();

        return ['string', strlen($string)];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws SwoftException
     */
    public function sendBigString(): array
    {
        $content = Co::readFile(__DIR__ . '/../../Rpc/Service/big.data');

        $len    = strlen($content);
        $result = $this->userService->sendBigContent($content);
        return [$len, $result];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function returnNull(): array
    {
        $this->userService->returnNull();
        return [null];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     *
     * @throws Exception
     */
    public function exception(): array
    {
        $this->userService->exception();

        return ['exception'];
    }
}
