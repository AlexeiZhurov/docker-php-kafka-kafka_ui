<?php
//Создание сообщения
error_reporting(E_ALL);

use RdKafka\Producer;
use RdKafka\TopicConf;

$conf = new RdKafka\Conf();
$conf->set('metadata.broker.list', 'kafka:9092');

$producer = new Producer($conf);

$tc = new TopicConf();

$topic = $producer->newTopic("helloworld");

if (!$producer->getMetadata(false, $topic, 2000)) {
    echo "Ошибка получения данных от брокера";
    exit;
}

$i = 0;

while ($i < 100) {
    $data = json_decode(file_get_contents("https://type.fit/api/quotes"))[$i]->text;//Получеие случайны сообщений для разнообразия
    $i++;
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "Сообщние:".$data);
}

$producer->flush(200);

echo "Message published\n";