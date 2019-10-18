<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Entity\User;
use Exception;
use Swoft\Co;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\Log;
use Swoft\Redis\Redis;
use Swoole\Coroutine\Http\Client;
use Throwable;

/**
 * Class CoController
 *
 * @since 2.0
 *
 * @Controller()
 */
class CoController
{
    /**
     * @RequestMapping()
     *
     * @return array
     *
     * @throws Exception
     */
    public function multi(): array
    {
        Log::debug(__METHOD__.' begin id:'.Co::id());
        $requests = [
            'addUser' => [$this, 'addUser'],
            'getUser' => "App\Http\Controller\CoController::getUser",
            'curl'    => function () {
                $cli = new Client('127.0.0.1', 18306);
                $cli->get('/redis/str?ip=192.168.7.8');
                $result = $cli->body;
                Log::debug(__METHOD__.' curl result:'.json_encode($result));
                $cli->close();
                return $result;
            }
        ];

        $response = Co::multi($requests);

        Log::debug(__METHOD__.' end response:'.json_encode($response));

        return $response;
    }

    /**
     * @return array
     */
    public static function getUser(): array
    {
        $result = Redis::set('test_name', 'liwang');
        $curVal = Redis::get('test_name');
        Log::debug(__METHOD__.'  id:'.Co::id(). ' curVal'.$curVal);

        return [$result, $curVal];
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function addUser(): array
    {
        Log::debug(__METHOD__.'  id:'.Co::id());
        $user = User::new();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');
        $user->setName('multi3');

        // Save result
        $result = $user->save();

        Log::debug(__METHOD__.'  result:',$result.' user:'.json_encode($user));

        return [$result, $user->getId()];
    }
}