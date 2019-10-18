<?php
/**
 * Created by PhpStorm.
 * User: user_1234
 * Date: 2019/9/23
 * Time: 11:59
 */

namespace App\service\impl;


use App\service\ISmsInterface;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class QcloudSms
 * Notes:
 * User: user_1234
 * DateTime: 2019/9/23 11:59
 * @package App\service\impl
 * @Bean()
 */
class QcloudSms implements ISmsInterface
{

    public function send(string $content): bool
    {
        // TODO: Implement send() method.
        return true;
    }
}