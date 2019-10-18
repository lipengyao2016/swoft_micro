<?php declare(strict_types=1);


namespace App\Http\Controller;

use Exception;
use function sgo;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\Log;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Pool;
use Swoft\Redis\Redis;

/**
 * Class RedisController
 *
 * @since 2.0
 * @Controller("redis")
 */
class RedisController
{

    /**
     * @Inject()
     *
     * @var Pool
     */
    private $redis;

    /**
     * @RequestMapping("poolSet")
     */
    public function poolSet(): array
    {
        $key   = 'poolSet_Key';
        $value = uniqid();

        $this->redis->set($key, $value);

        $get = $this->redis->get($key);

        Log::debug(__METHOD__.' value:'.$get);

        $isError = $this->redis->call(function (\Redis $redis) {
            $ret = $redis->eval('return 1');
            Log::debug(__METHOD__.' ret:'.$ret);
            return $redis->getLastError();
        });

        return [$get, $value, $isError];
    }

    /**
     * @RequestMapping()
     */
    public function set(): array
    {
        $key   = 'key';
        $value = uniqid();
        Log::debug(__METHOD__.' value:'.$value);
        $addRet = $this->redis->zAdd($key, [
            'temp'    => 11.1,
            'single' => 11.3,
            'rain' => 11.21,
            'goto' => 10.01,
            'break' => 11.15
        ]);


        Log::debug(__METHOD__.' addRet:'.$addRet);

        $get = $this->redis->zRange($key,0,-1);
        Log::debug(__METHOD__.' get:'.json_encode($get));

        return [$get, $value];
    }


    /**
     * @RequestMapping("str")
     */
    public function str(Request $request): array
    {
        $curIP = $request->input('ip','test_127.0.0.1');

        $key    = 'str_key';
        $result = Redis::set($key, 'data_simple');

        $keyVal = Redis::get($key);
        Log::debug(__METHOD__.' result:'.$result. ' keyVal:'.$keyVal);
        $isError = Redis::call(function (\Redis $redis) use ($curIP) {
           // $ret = $redis->eval('return 1');
        /*    $ret = $redis->eval('if redis.call(\'get\',KEYS[1]) then return 1 else redis.call(\'set\', KEYS[1], \'test\') return 0 end',
                [$curIP],1);*/
            $luaScript = 'redis.call(\'rpush\', ARGV[1],ARGV[2]);if (redis.call(\'llen\',ARGV[1]) >tonumber(ARGV[3])) then if tonumber(ARGV[2])-redis.call(\'lpop\', ARGV[1])<tonumber(ARGV[4]) then return -1 else return 1 end else return 1 end';
            $ret = $redis->eval($luaScript, [$curIP,time(),5,5],0);

            Log::debug(__METHOD__.' ret:'.$ret);
            return $redis->getLastError();
        });

        $data = [
            $result,
            $keyVal,
            $isError
        ];

        return $data;
    }

    /**
     * Only to use test. The wrong way to use it
     *
     * @RequestMapping("release")
     *
     * @return array
     * @throws RedisException
     */
    public function release(): array
    {
        sgo(function () {
            Redis::connection()->set('t2','d2');
        });

        Redis::connection()->set('t1','data');

        return ['release'];
    }

    /**
     * Only to use test. The wrong way to use it
     *
     * @RequestMapping("ep")
     *
     * @return array
     */
    public function exPipeline(): array
    {
        sgo(function () {
            Redis::pipeline(function () {
                throw new Exception('');
            });
        });
        Redis::set('p4',50);

        Redis::pipeline(function () {
            //throw new Exception('');
            for($i = 100;$i < 150;$i++)
            {
                Redis::set('p1',20);
                Redis::incr('p3');
                Redis::hSet('set1','name','lily');
                Redis::lPush('l1','list_val'.$i);
                Redis::decr('p4');
            }
            Log::debug(__METHOD__.' ret:');
        });

        return ['exPipeline'];
    }

    /**
     * Only to use test. The wrong way to use it
     *
     * @RequestMapping("et")
     *
     * @return array
     */
    public function exTransaction(): array
    {
        sgo(function () {
            Redis::transaction(function () {
               // throw new Exception('');
                Redis::set('q5',20);
                Redis::set('q6',50);
                Redis::get('q7');

              //  $luaScript = 'redis.call(\'rpush\', ARGV[1],ARGV[2]);if (redis.call(\'llen\',ARGV[1]) >tonumber(ARGV[3])) then if tonumber(ARGV[2])-redis.call(\'lpop\', ARGV[1])<tonumber(ARGV[4]) then return -1 else return 1 end else return 1 end';
               // $ret = Redis::eval($luaScript, ['192.168.7.20',time()],0);
            });
        });

        Redis::transaction(function () {
            //throw new Exception('');
            Redis::set('a1',20);
            Redis::set('a2');
        });

        return ['exPipeline'];
    }
}
