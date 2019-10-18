<?php declare(strict_types=1);


namespace App\Http\Controller;

use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Limiter\Annotation\Mapping\RateLimiter;
use Swoft\Log\Helper\Log;

/**
 * Class LimiterController
 *
 * @since 2.0
 *
 * @Controller(prefix="limiter")
 */
class LimiterController
{
    /**
     * @RequestMapping()
     * @RateLimiter(key="request.getUriPath()",rate=20)
     *
     * @param Request $request
     *
     * @return array
     */
    public function requestLimiter(Request $request): array
    {
        $uri = $request->getUriPath();
        Log::debug(__METHOD__.' uri:%s',$uri);
        return ['requestLimiter', $uri];
    }

    /**
     * @RequestMapping()
     * @RateLimiter(rate=20, fallback="limiterFallback")
     *
     * @param Request $request
     *
     * @return array
     */
    public function requestLimiter2(Request $request): array
    {
        $uri = $request->getUriPath();
        Log::debug(__METHOD__.' uri:%s',$uri);
        return ['requestLimiter2', $uri];
    }

    /**
     * @RequestMapping()
     * @RateLimiter(key="request.getUriPath()~':'~request.query('id')",rate=20, fallback="limiterFallback")
     *
     * @param Request $request
     *
     * @return array
     */
    public function paramLimiter(Request $request): array
    {
        $id = $request->query('id');
        return ['paramLimiter', $id];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function limiterFallback(Request $request): array
    {
        $uri = $request->getUriPath();
        Log::debug(__METHOD__.' uri:%s',$uri);
        return ['limiterFallback', $uri];
    }
}
