<?php
/**
 * Created by PhpStorm.
 * User: user_1234
 * Date: 2019/9/23
 * Time: 11:56
 */

namespace App\service;


interface ISmsInterface
{
    public function send(string $content): bool;
}