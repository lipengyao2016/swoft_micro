<?php declare(strict_types=1);


namespace App\Model\Logic;

use App\Common\kafka\Producer;
use Swoft\Apollo\Config;
use Swoft\Apollo\Exception\ApolloException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;


/**
 * Class ApolloLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class KafkaProducterLogic
{
    protected $kafkaServerHost;

    protected $topicName;

    protected $rk;

    protected $topic;

    protected $config;

    protected $producer;



    /**
     * ApolloLogic constructor.
     */
    public function __construct()
    {
       /* $this->kafkaServerHost = '47.107.246.243';
        $this->topicName = 'swoft_test';

        $this->rk = new \RdKafka\Producer();

        print_r($this->rk);
        // $rk->setLogLevel(LOG_DEBUG);
        $this->rk->addBrokers($this->kafkaServerHost);

        $this->topic = $this->rk->newTopic($this->topicName);
        print_r($this->topic);*/

        $this->config = [
            'ip'=>'47.107.246.243',
            'dr_msg_cb' => function($kafka, $message) {
                print_r((array)$message);
            }
        ];


    }


    /**
     * @throws ApolloException
     */
    public function productMsg()
    {
        if(!$this->producer)
        {
            print_r(' first create producer');
            $this->producer = new Producer($this->config);
            $this->topic = $this->producer->setBrokerServer()->setProducerTopic('test_lpy');
        }
       // print_r($this->producer);
      //  print_r($this->topic);
        $sendRet = $this->topic->producer('liming_swoft_server',90);
        print_r(" sendRet:".$sendRet);

        return $sendRet;
       /* for ($i = 0; $i < 10; $i++) {
            $sendRet = $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, "data swoft lipy msg  $i");
            print_r(" sendRet:".$sendRet);
        }*/
    }


}