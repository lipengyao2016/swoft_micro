<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use function context;
use Swoft\Log\Helper\Log;

/**
 * Class FavIconMiddleware
 *
 * @Bean()
 */
class FavIconMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface|Request  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        Log::debug(__METHOD__.' ----------------begin method: '.$request->getMethod().' uri:'.$request->getUriPath());
        if($request->getMethod() == 'GET')
        {
            Log::debug(__METHOD__.' queryParams: '.json_encode( $request->getQueryParams()));
        }
        else
        {
            Log::debug(__METHOD__.' body: '.json_encode( $request->getParsedBody()));
        }

        if ($request->getUriPath() === '/favicon.ico') {
            return context()->getResponse()->withStatus(404);
        }

        $respone = $handler->handle($request);

        Log::debug(__METHOD__.' ---------------------end method: '.$request->getMethod().' uri:'.$request->getUriPath());
        return $respone;
    }
}
