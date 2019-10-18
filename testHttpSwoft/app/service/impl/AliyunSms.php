<?php
/**
 * Created by PhpStorm.
 * User: user_1234
 * Date: 2019/9/23
 * Time: 11:57
 */

namespace App\service\impl;


use App\service\ISmsInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Primary;
use Swoft\Bean\BeanFactory;
use Swoft\Co;
use Swoft\Log\Helper\Log;

/**
 * Class AliyunSms
 * Notes:
 * User: user_1234
 * DateTime: 2019/9/23 11:57
 * @package App\service\impl
 * @Bean()
 * @Primary()
 */
class AliyunSms implements ISmsInterface
{

    public function send(string $content): bool
    {
        // TODO: Implement send() method.
        Log::profileStart(__METHOD__);
        $id = (string)Co::tid();
        $request = BeanFactory::getRequestBean('requestBean', $id);
        Log::debug(__METHOD__.' TID:'.$id.' req:'.get_class($request).' data:'.json_encode($request->getData()));
        Log::profileEnd(__METHOD__);
        return true;
    }
}