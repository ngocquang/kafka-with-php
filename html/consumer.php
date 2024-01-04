<?php


$conf = new RdKafka\Conf();
// $conf->set('log_level', (string) LOG_DEBUG);
// $conf->set('debug', 'all');
$conf->set('enable.partition.eof', 'true');
$conf->set('auto.offset.reset', 'earliest');
$conf->set('metadata.broker.list', 'kafka:9092');
$conf->set('group.id', 'my-service-consumer');
$conf->set('auto.offset.reset', 'earliest');
$rk = new RdKafka\Consumer($conf);

$topics_name = ["test"];

$consumer = new \RdKafka\KafkaConsumer($conf);
// Subscribe to topic topic_name
$consumer->subscribe($topics_name);

echo "Waiting for partition assignment... (make take some time when\n";
echo "quickly re-joining the group after leaving it.)\n";

while (true) {
    $message = $consumer->consume(120*1000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo $message->payload."\n";
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; will wait for more\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
            throw new \Exception($message->errstr(), $message->err);
            break;
    }
}


