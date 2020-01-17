<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/12
 * Time: 9:49
 */

namespace App\Common;


use Swoft\Http\Message\Request;
use Swoft\Log\Helper\Log;

class HttpRespUtils
{
    public static function getRequestData(Request $request)
    {
        $data = $request->input();
        $data['agent'] = HttpRespUtils::getPlatformByUserAgent($request);
       // Log::debug(__METHOD__.' header:'.json_encode($request->header()));
        $uid = count($request->header('uid')) >0 ? $request->header('uid')[0] : 0 ;
        if(!isset($data['uid']))
        {
            $data['uid'] = $uid;
        }
      //  Log::info(__METHOD__.' url:'.$request->getUriPath().' data:'.json_encode($data));
        return $data;
    }

    public static function makeUrl($serverName,$path)
    {
        $serverHost = config('application.'.$serverName.'_host');
        $serverPort = config('application.'.$serverName.'_port');
        return 'http://'.$serverHost.':'.$serverPort.$path;
    }

    public static function getPlatformByUserAgent(Request $request)
    {
        return self::getPartByUserAgent($request,2);
    }

    public  static  function  getPartByUserAgent(Request $request,$num)
    {
        $userAgent = $request->getHeader('user-agent');
        if(count($userAgent) > 0)
        {
            $userAgent = explode('|',$userAgent[0]);
           // Log::debug(__METHOD__.' userAgent:'.json_encode($userAgent));
            if(isset($userAgent[$num]) && !empty($userAgent[$num])){
                $data = $userAgent[$num];
                return $data;
            }
        }
        return '';
    }

    public  static  function responseErrorData($code = '200', $msg = '',$results = [])
    {
       return self::responseData($results,false,$code,$msg);
    }

    public  static  function responseFuncData($code = 0, $results = [],$msg = '')
    {
        return ['code'=>$code,'results' => $results,'msg' => $msg];
    }

    public  static  function responseSucedFuncData( $results = [])
    {
        return ['code'=>0,'results' => $results,'msg' => ''];
    }

    public  static  function responseErrorFuncData( $code = 0,$msg = '',$results = [])
    {
        return ['code'=>$code,'results' => $results,'msg' => $msg];
    }

    public  static  function responseSucData($result, $msg = '')
    {
        return self::responseData($result,false,200,$msg ? $msg : '请求成功');
    }

    public  static  function responseData($result, $has_next = true, $code = '200', $msg = '')
    {
        $meta = ['code' => $code, 'msg' => $msg ? $msg : self::get_err_name($code),
            'has_next' => $has_next];
        $data = ['meta' => $meta, 'results' => $result];
        return $data;
    }


    public static function get_err_name($err_code)
    {
        $key = 'maggie_err_code:' . $err_code;
        $cache =  RedisUtils::cmd(3,$key,RedisUtils::$REDIS_CMD_KEY_GET);
        if ($cache) {
            return $cache;
        } else {
            $sjStateModel = \Swoft\Db\DB::table('state_code');
            $_err_name = $sjStateModel->where(['err_code' => $err_code])->first();
            if ($_err_name) {
                RedisUtils::cmd(3,$key,RedisUtils::$REDIS_CMD_KEY_SET,60*60*2,$_err_name['err_name']);
                return $_err_name['err_name'];
            }
        }
        return '未知错误，请联系相关人员';//数据库不存在对应的错误码信息，请开发人员自行检查
    }
}