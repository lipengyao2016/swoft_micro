<?php

/*$rk = new RdKafka\Producer();
print_r($rk);
$rk->setLogLevel(LOG_DEBUG);
$rk->addBrokers("47.107.246.243");

$topic = $rk->newTopic("test_lpy");

print_r($topic);

for ($i = 0; $i < 10; $i++) {
    $sendRet = $topic->produce(RD_KAFKA_PARTITION_UA, 0, "data lipy $i");
    print_r(" sendRet:".$sendRet);
}*/

use App\Model\kafka\Producer;

$config = [
    'ip'=>'47.107.246.243',
    'dr_msg_cb' => function($kafka, $message) {
        var_dump((array)$message);
        //todo
        //do biz something, don't exit() or die()
    }
];
$producer = new Producer($config);
$rst = $producer->setBrokerServer()
    ->setProducerTopic('test_lpy')
    ->producer('qkl037', 90);

var_dump($rst);