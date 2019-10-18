<?php
/**
 * Created by PhpStorm.
 * User: user_1234
 * Date: 2019/10/12
 * Time: 20:27
 */

namespace App\Common;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\Log;

/**
 * Class NetworkUtils
 * Notes:
 * User: user_1234
 * DateTime: 2019/10/12 20:28
 * @package App\Common
 * @Bean()
 */
class NetworkUtils
{

    public  function get_server_ip()
    {
        $server_ip = '';
        return '127.0.0.1';
        Log::debug(__METHOD__.' server  info:'.json_encode($_SERVER));
        if (isset($_SERVER['SERVER_NAME'])) {
            Log::debug(__METHOD__.' servername:'.$_SERVER['SERVER_NAME']);
            return gethostbyname($_SERVER['SERVER_NAME']);
        } else {
            if (isset($_SERVER)) {
                if (isset($_SERVER['SERVER_ADDR'])) {
                    Log::debug(__METHOD__.' SERVER_ADDR:'.$_SERVER['SERVER_ADDR']);
                    $server_ip = $_SERVER['SERVER_ADDR'];
                } elseif (isset($_SERVER['LOCAL_ADDR'])) {
                    Log::debug(__METHOD__.' LOCAL_ADDR:'.$_SERVER['LOCAL_ADDR']);
                    $server_ip = $_SERVER['LOCAL_ADDR'];
                }
            } else {
                $server_ip = getenv('SERVER_ADDR');
                Log::debug(__METHOD__.' env server addr:'.$server_ip);
            }
            return $server_ip ? $server_ip : '获取不到服务器IP';
        }
    }


}