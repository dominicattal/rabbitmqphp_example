#!/bin/php
<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection_send = new AMQPStreamConnection('100.72.42.128', 5672, 'dom', 'attal');
$channel_send = $connection_send->channel();
$connection_receive= new AMQPStreamConnection('localhost', 5672, 'test', 'test');
$channel_receive= $connection_receive->channel();
$channel_receive->queue_declare('queue_db_broker', false, false, false, false);
$channel_send->queue_declare('queue_broker_db', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->getBody(), "\n";
};
$arr = array();
$arr["username"] = "test";
$arr["password"] = "test";
$arrayMessage = json_encode($arr);
$msg = new AMQPMessage($arrayMessage);
$channel_send->basic_publish($msg, '', 'queue_broker_db');
$channel_receive->basic_consume('queue_db_broker', '', false, true, false, false, $callback);

try {
    $channel_receive->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

?>
