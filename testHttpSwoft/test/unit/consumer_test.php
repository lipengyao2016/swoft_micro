<?php

$rk = new RdKafka\Consumer();
//$rk->setLogLevel(LOG_DEBUG);
$rk->addBrokers("47.107.246.243");

$topic = $rk->newTopic("test_lpy");

$topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);

while (true) {
    $msg = $topic->consume(0, 1000);
    if (null === $msg) {
        continue;
    }
    /* elseif ($msg->err) {
        echo $msg->errstr(), "\n";
        break;
    } */
    else {
        echo $msg->payload, "\n";
    }
}
