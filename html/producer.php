<?php

$conf = new RdKafka\Conf();
// $conf->set('log_level', (string) LOG_DEBUG);
// $conf->set('debug', 'all');
$conf->set('metadata.broker.list', 'kafka:9092');
$rk = new RdKafka\Producer($conf);

# Create the topic object with that handle or use it if it already exists.
$topic = $rk->newTopic("test");

# Produce 10 messages
echo "Producing...\n";
for ($i = 0; $i < 10; $i++) {
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "Message $i");
}
echo "Finished\n";
$rk->flush(1000);
