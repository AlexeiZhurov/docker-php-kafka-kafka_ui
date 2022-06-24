<?php
$conf = new RdKafka\Conf();

$conf->set('group.id', 'group_1');//Создаем групуп читателей
$conf->set('log_level', (string)LOG_DEBUG);
$conf->set('metadata.broker.list', 'kafka:9092');//Указываем откуда брать список брокеров
//$conf->set('debug', 'all');

$rk = new RdKafka\Consumer($conf);//Создаем нового слушателя

$topicConf = new RdKafka\TopicConf();//Создаем экземпляр настроек топика
$topicConf->set('auto.commit.interval.ms', 9999999);

//Указание параметра на тот случай если не было установленно с какова сообщения продолжать читаь=ь
$topicConf->set('auto.offset.reset', 'smallest');
$topic = $rk->newTopic("helloworld", $topicConf);//Подключаемся к топику helloworld

$partition = 0;//Индитификатор раздела
// RD_KAFKA_OFFSET_STORED - читать с того места где остановились в прошлый раз(этот флаг возможно использовать только в случае указания - 'group.id')
$topic->consumeStart($partition, RD_KAFKA_OFFSET_STORED);//Начинаем прочтение

while (true) {
    
    $msg = $topic->consume($partition, 1000);//Получения сообщения и таймаут между получением

    if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
        print_r('sleep...');
        sleep(2);
        continue;
    } elseif ($msg->err) {
        echo $msg->errstr(), "\n";//вывод ошибки при получение сообщения
        break;
    } else {
        // echo 'partition -' . $msg->partition, "\n";
        echo $msg->payload, "\n";
        //Сохраниение смещения
        $topic->offsetStore($partition, $msg->offset);
    }
}