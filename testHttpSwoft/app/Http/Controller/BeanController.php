<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Common\ExcelUtils;
use App\Common\FileUploadUtils;
use App\Common\HttpRespUtils;
use App\Model\Logic\ApolloLogic;
use App\Model\Logic\KafkaProducterLogic;
use App\Model\Logic\MongoDBLogic;
use App\Model\Logic\RequestBean;
use App\Model\Logic\RequestBeanTwo;
use App\service\ISmsInterface;
use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Co;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;

/**
 * Class BeanController
 *
 * @since 2.0
 *
 * @Controller(prefix="bean")
 */
class BeanController
{

    /**
     * @Inject()
     * @var ISmsInterface
     */
    protected $smsInterface;

    /**  @Inject()
     * @var ApolloLogic
     */
    protected $apolloLogic;


    /**  @Inject()
     * @var KafkaProducterLogic
     */
    protected $kafkaProducterLogic;


    /**  @Inject()
     * @var MongoDBLogic
     */
    protected $mongoDBLogic;


    /**
     * @RequestMapping()
     *
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     * @throws \Swoft\Exception\SwoftException
     */
    public function request(): array
    {
        $id = (string)Co::tid();

        $context = context();

        $dbName = config(null,'null');
        CLog::info(__METHOD__.' dbName:'.json_encode($dbName));

        $db = \Swoft::getBean('db');
        CLog::info(__METHOD__.' dsn:'.$db->getDsn().' userName:'
            .$db->getUsername().' password:'.$db->getPassword());

        /** @var RequestBean $request*/
        $request = BeanFactory::getRequestBean('requestBean', $id);
        CLog::debug(__METHOD__.' TID:'.$id.' req:'.get_class($request).
        //' swoole process id:'.$context->getSwooleServer()->worker_pid.
        ' cur process id:'.posix_getpid());

        $request->setData('sms'.$id);

        $this->smsInterface->send("hello,worldd");

//        $server = bean('httpServer');
//        $server->restart();

        return $request->getData();
    }

    /**
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     *
     * @RequestMapping()
     */
    public function requestClass(Request $request): array
    {
        $id = (string)Co::tid();

       // $headers = $request->getHeaders();
        //CLog::info(__METHOD__.' headers:'.json_encode($headers));

        /* @var RequestBeanTwo $request */
       // $request = BeanFactory::getRequestBean(RequestBeanTwo::class, $id);
       // Log::debug(__METHOD__.' TID:'.$id.' cls:'.get_class($request));

        $bRet = $this->kafkaProducterLogic->productMsg();

        return ['ret' => $bRet];

       // return $request->getData();
    }

    /**
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     *
     * @RequestMapping()
     */
    public function insertMongodb(Request $request): array
    {
        $id = (string)Co::tid();

        $headers = $request->getHeaders();
        CLog::info(__METHOD__.' headers:'.json_encode($headers));

        $datas = [];
        for ($i = 0;$i < 5;$i++)
        {
            $dataItem = ['id' => 10, 'age' => 25, 'name' => 'xiaowang'];
            $dataItem['id'] += $i;
            $dataItem['name'] .= $i;
            array_push($datas,$dataItem);
        }
        $insertRet = $this->mongoDBLogic->getMongoDBDao()->insertMany($datas);
        CLog::info(__METHOD__.' $insertRet:'.json_encode($insertRet));
        return ['ret' => $insertRet];
        // return $request->getData();
    }

    /**
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     *
     * @RequestMapping()
     */
    public function queryMongodb(Request $request): array
    {
        $id = (string)Co::tid();

        $headers = $request->getHeaders();
        CLog::info(__METHOD__.' headers:'.json_encode($headers));

        $datas = [];

        $findRet = $this->mongoDBLogic->getMongoDBDao()->findByPage(['age' => 22],['_id','id','name','age'],null,false
            ,2,2);
        CLog::info(__METHOD__.' $findRet:'.json_encode($findRet));
        return ['ret' => $findRet];
        // return $request->getData();
    }

    /**
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     *
     * @RequestMapping()
     */
    public function uploadFile(Request $request): array
    {
       /* $findRet = 'ok';
        $files = $request->getUploadedFiles();
        $file = $files['file'];
        CLog::info(__METHOD__.' $files:'.json_encode($files));
        $dir = alias('@runtime/uploadfiles');
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $path = $dir . '/' . 'name' . '.jpg';
        $file->moveTo($path);

        CLog::info(__METHOD__.' uploadfile ok.');
        return ['ret' => $findRet];*/

        Log::info(__METHOD__.' 11');
        $file = $request->file("file");
        if(empty($file)){
            return ["error"=>1];
        }
        Log::info(__METHOD__.' 22');
        $arr = $file->toArray();
        if(isset($arr["error"]) && $arr["error"] != 0){
            return ["error"=>1];
        }
        Log::info(__METHOD__.' 33 arr:'.json_encode($arr));
        $name = $arr["fileName"];
        $type = $arr["type"];
        $size = $arr["size"];
        Log::info(__METHOD__.' name:'.$name.' type:'.$type.' $size:'.$size);

        $filename = $name;
        $target_dir =  alias('@runtime/uploadfiles');
        Log::info(__METHOD__.' $target_dir:'.$target_dir);
        if(!is_dir($target_dir)){
            mkdir($target_dir);
        }

        $target_path = $target_dir."/".$filename;
        Log::info(__METHOD__.' $target_path:'.$target_path);
        $result = $file->moveTo($target_path);

        return ['ret' => 'ok'];
    }

    /**
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     *
     * @RequestMapping()
     */
    public function importExcelData(Request $request): array
    {
        $uploadFileData = FileUploadUtils::getUploadFilePath($request);
        Log::info(__METHOD__.' $uploadFileData:'.$uploadFileData);
        if($uploadFileData['code'] == 0)
        {
            $data = ExcelUtils::importExcel($uploadFileData['results']['path']);
            Log::info(__METHOD__.' data:'.json_encode($data));
            return HttpRespUtils::responseSucData(['ret' => 'ok']);
        }
        else{
            return HttpRespUtils::responseErrorData($uploadFileData['code'],$uploadFileData['msg']);
        }

    }


    /**
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     *
     * @RequestMapping()
     */
    public function exportExcelData(Request $request): array
    {
        $data = [];
        $i = 0;
        $saveFilePath = '';
        for ($i = 0; $i < 10;$i++)
        {
            array_push($data,[
                'name' => 'lily_'.$i,
                'sex' => 'man_'.$i,
            ]);
        }
        $excelFileName = 'dowload.xls';
        $downloadFilePath = FileUploadUtils::getUploadDir().'/'.$excelFileName;
        Log::info(__METHOD__.' $downloadFilePath:'.$downloadFilePath);
        $ret = ExcelUtils::exportExcel($data,$excelFileName,['savePath'=>$downloadFilePath]);
        Log::info(__METHOD__.' $ret:'.json_encode($ret));
        return ['ret' => 'ok'];
    }

}