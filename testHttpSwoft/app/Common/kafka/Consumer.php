<?php
/**
 * Created by PhpStorm.
 * User: qkl
 * Date: 2018/8/14
 * Time: 15:45
 */

namespace App\Common\kafka;
use Swoft\Log\Helper\CLog;

class Consumer
{
    private $consumer;
    private $consumerTopic;

    public function __construct($config = [])
    {
        $this->rk = new Rdkafka($config);
        $this->rkConf = $this->rk->getConf();
        $this->config = $this->rk->getConfig();
        $this->brokerConfig = $this->rk->getBrokerConfig();
    }

    /**
     * 设置消费组
     * @param $groupName
     */
    public function setConsumerGroup($groupName)
    {
        $this->rkConf->set('group.id', $groupName);
        print_r(' setConsumerGroup ok.');
        return $this;
    }

    /**
     * 设置服务broker
     * $broker: 127.0.0.1|127.0.0.1:9092|127.0.0.1:9092,127.0.0.1:9093
     * @param $groupName
     */
    public function setBrokerServer($broker)
    {
        $this->rkConf->set('metadata.broker.list', $broker);
        print_r(' setBrokerServer ok.');
        return $this;
    }

    /**
     * 设置服务broker
     * $broker: 127.0.0.1|127.0.0.1:9092|127.0.0.1:9092,127.0.0.1:9093
     * @param $groupName
     */
    public function setTopic($topicName, $partition = 0, $offset = 0)
    {
        $this->rk->setTopic($topicName, $partition, $offset);
        print_r(__METHOD__.'  ok.');
        return $this;
    }

    public function setConsumerTopic()
    {
      //  $this->topicConf = new \RdKafka\TopicConf();

        print_r(' setConsumerTopic 1.');

        $this->rkConf->set('auto.offset.reset', 'smallest');
        //$this->rkConf->set('auto.commit.enable', false);

        //$this->topicConf->set('request.required.acks', $this->brokerConfig['request.required.acks']);
        //在interval.ms的时间内自动提交确认、建议不要启动
       /* $this->topicConf->set('auto.commit.enable', $this->brokerConfig['auto.commit.enable']);
        if ($this->brokerConfig['auto.commit.enable']) {
            $this->topicConf->set('auto.commit.interval.ms', $this->brokerConfig['auto.commit.interval.ms']);
        }*/
        print_r(' setConsumerTopic 2.');
        // 设置offset的存储为file
//        $this->topicConf->set('offset.store.method', 'file');
//        $this->topicConf->set('offset.store.path', __DIR__);
        // 设置offset的存储为broker
       // $this->rkConf->set('offset.store.method', 'broker');
        // $this->topicConf->set('offset.store.method', $this->brokerConfig['offset.store.method']);
       // if ($this->brokerConfig['offset.store.method'] == 'file') {
        //    $this->topicConf->set('offset.store.path', $this->brokerConfig['offset.store.path']);
      //  }
       // print_r(' setConsumerTopic 3.');
        // Set where to start consuming messages when there is no initial offset in
        // offset store or the desired offset is out of range.
        // 'smallest': start from the beginning
       // $this->topicConf->set('auto.offset.reset', 'smallest');
        //$this->topicConf->set('auto.offset.reset', $this->brokerConfig['auto.offset.reset']);
       // print_r(' setConsumerTopic 4.');
        //设置默认话题配置
        //$this->rkConf->setDefaultTopicConf($this->topicConf);

        print_r(' setConsumerTopic ok.');

        return $this;
    }

    public function getConsumerTopic()
    {
        return $this->topicConf;
    }

    public function subscribe($topicNames)
    {
        $this->consumer = new \RdKafka\KafkaConsumer($this->rkConf);
        $this->consumer->subscribe($topicNames);
        print_r(' subscribe end.');
        return $this;
    }

    public function consumer(\Closure $handle)
    {
        print_r(' consumer start.');
        while (true) {
            $message = $this->consumer->consume(12*1000);
            if (null === $message) {
                CLog::info(' recv null msg  pid:'.posix_getpid());
                continue;
            }
            elseif ($message->err) {
                CLog::info(' recv error msg  pid:'.$message->errstr());
            }
            else {
                print_r($message);
                CLog::info(' recv unit pid:'. posix_getpid().' msg:'. $message->payload);
                $handle($message);
            }
          /*  switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $handle($message);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    CLog::info(' pid:'.posix_getpid()."No more messages; will wait for more\n");
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    CLog::warning(' pid:'.posix_getpid()."Timed out .\n");
                    break;
                default:
                    //throw new \Exception($message->errstr(), $message->err);
                    CLog::error(' pid:'.posix_getpid()." msg error:".$message->errstr().' err:'.$message->err);
                    break;
            }*/
        }
        print_r(' consumer end.');
    }

    public function consumer2(\Closure $callback)
    {
        //参数1表示消费分区，这里是分区0
        //参数2表示同步阻塞多久
        $message = $this->consumerTopic->consume(0, 12 * 1000);
        var_dump($message);
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                //todo 消费
                $callback($message);
                break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                CLog::info("No more messages; will wait for more\n");
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                CLog::info("Timed out\n");
                break;
            default:
                echo $message->err . ":" . $message->errstr;
//                throw new \Exception($message->errstr(), $message->err);
                break;
        }
//        while (true) {
//            //参数1表示消费分区，这里是分区0
//            //参数2表示同步阻塞多久
//            $message = $this->consumerTopic->consume(0, 12 * 1000);
//            switch ($message->err) {
//                case RD_KAFKA_RESP_ERR_NO_ERROR:
//                    //todo 消费
//                    $callback($message);
//                    break;
//                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
//                    echo "No more messages; will wait for more\n";
//                    break;
//                case RD_KAFKA_RESP_ERR__TIMED_OUT:
//                    echo "Timed out\n";
//                    break;
//                default:
//                    throw new \Exception($message->errstr(), $message->err);
//                    break;
//            }
//        }
    }
}