<?php declare(strict_types=1);


namespace App\Model\Logic;

use App\Common\kafka\Consumer;
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
class KafkaConsumerLogic
{

    protected $kafkaServerHost;

    protected $topicName;


    /**
     * ApolloLogic constructor.
     */
    public function __construct()
    {
        $this->kafkaServerHost = '47.107.246.243';
        $this->topicName = 'test_lpy';
    }


    /**
     * @throws ApolloException
     */
    public function consumer()
    {
      /*  $rk = new \RdKafka\Consumer();
        print_r($rk);
        $rk->addBrokers($this->kafkaServerHost);

        $topic = $rk->newTopic($this->topicName);
        print_r($topic);

        $topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);
        CLog::info(' consumer start msg');
        while (true) {
            $msg = $topic->consume(0, 12000);
            if (null === $msg) {
                CLog::info(' recv null msg  pid:'.posix_getpid());
                continue;
            }
             elseif ($msg->err) {
                 CLog::info(' recv error msg  pid:'.$msg->errstr());
            }
            else {
                print_r($msg);
                CLog::info(' recv unit pid:'. posix_getpid().' msg:'. $msg->payload);
            }
        }*/
        $offset = 0;
        $consumer = new Consumer(['ip'=>$this->kafkaServerHost]);
        //print_r($consumer);
        print_r(' consumer begin.');


        try{
            $consumer->setConsumerGroup('test-swoft-server')
                ->setBrokerServer($this->kafkaServerHost)
                ->setConsumerTopic()
               // ->setTopic($this->topicName, 0, $offset)
                ->subscribe([$this->topicName])
                ->consumer(function($msg){
                    CLog::info(' recv unit pid:'. posix_getpid().' msg:'.json_encode($msg));
                });
        }
        catch (\Exception $e)
        {
           // Log::error($e->getTraceAsString());
           // print_r($e->getTrace());
        }

        print_r(' consumer exit.');

    }



}