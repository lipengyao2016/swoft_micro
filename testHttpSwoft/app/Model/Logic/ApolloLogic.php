<?php declare(strict_types=1);


namespace App\Model\Logic;

use Swoft\Apollo\Config;
use Swoft\Apollo\Exception\ApolloException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Log\Helper\Log;

/**
 * Class ApolloLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class ApolloLogic
{
    /**
     * @Inject()
     *
     * @var Config
     */
    private $config;

    private $configData;

    /**
     * ApolloLogic constructor.
     */
    public function __construct()
    {
        $this->configData = null;
    }



    /**
     * @throws ApolloException
     */
    public function pull()
    {
        $nameSpaces = ['application'/*,'TEST1.shop','merchant'*/];
      /*  foreach ($nameSpaces as $curNameSpace)
        {
            $data = $this->config->pull($curNameSpace);
            Log::debug(' curNameSpace:%s data:%s',$curNameSpace,json_encode($data));
            // Print data
            var_dump($data);
        }*/
       $retData = $this->config->batchPull($nameSpaces);
       Log::debug('  retData:%s',json_encode($retData));

       return $retData;
    }

    public function listen(): void
    {
        $nameSpaces = ['application','TEST1.shop','merchant'];

        $retData = $this->config->listen($nameSpaces,function (array $data)
        {
             Log::debug('listen notify data:%s',json_encode($data));
        });
        Log::debug('  retData:%s',json_encode($retData));
    }
}