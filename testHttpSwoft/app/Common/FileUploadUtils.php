<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/11
 * Time: 10:36
 */

namespace App\Common;
use Swoft\Co;
use Swoft\Http\Message\Request;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Swoft\Redis\Redis;
use Swoft\Redis\Exception\RedisException;
use Swoft\Redis\Pool;


class FileUploadUtils
{
    public static function getUploadDir()
    {
        $target_dir =  alias('@runtime/uploadfiles');
        if(!is_dir($target_dir)){
            mkdir($target_dir);
        }
        return $target_dir;
    }
    public static function getUploadFilePath(Request $request)
    {
        $file = $request->file("file");
        if(empty($file)){
            return HttpRespUtils::responseErrorFuncData(-1,'file is empty');
        }
        $arr = $file->toArray();
        if(isset($arr["error"]) && $arr["error"] != 0){
            return HttpRespUtils::responseErrorFuncData(-2,'file has error '.$arr['error']);
        }
        $name = $arr["fileName"];
        $target_path = self::getUploadDir()."/".$name;
        Log::info(__METHOD__.' $target_path:'.$target_path);
        if(file_exists($target_path))
        {
            unlink($target_path);
        }
        $result = $file->moveTo($target_path);
        return  HttpRespUtils::responseSucedFuncData(['path' => $target_path]);
    }

}