#!/bin/php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$config = parse_ini_file('testbroker.ini');

$MQ_BROKER_HOST = $config["MQ_BROKER_HOST"];
$MQ_BROKER_PORT = $config["MQ_BROKER_PORT"];
$MQ_BROKER_USER = $config["MQ_BROKER_USER"];
$MQ_BROKER_PASS = $config["MQ_BROKER_PASS"];
$MQ_DB_HOST = $config["MQ_DB_HOST"];
$MQ_DB_PORT = $config["MQ_DB_PORT"];
$MQ_DB_USER = $config["MQ_DB_USER"];
$MQ_DB_PASS = $config["MQ_DB_PASS"];
$MQ_QUEUE_DB_BROKER_NAME = $config["MQ_QUEUE_DB_BROKER_NAME"];
$MQ_QUEUE_BROKER_DB_NAME = $config["MQ_QUEUE_BROKER_DB_NAME"];

$connection_send = new AMQPStreamConnection($MQ_DB_HOST, $MQ_DB_PORT, $MQ_DB_USER, $MQ_DB_PASS);
$channel_send = $connection_send->channel();
$connection_receive= new AMQPStreamConnection($MQ_BROKER_HOST, $MQ_BROKER_PORT, $MQ_BROKER_USER, $MQ_BROKER_PASS);
$channel_receive= $connection_receive->channel();
$channel_receive->queue_declare($MQ_QUEUE_DB_BROKER_NAME, false, false, false, false);
$channel_send->queue_declare($MQ_QUEUE_BROKER_DB_NAME, false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->getBody(), "\n";
};
$arr = array();
$arr["username"] = "test";
$arr["password"] = "test";
$arrayMessage = json_encode($arr);
$msg = new AMQPMessage($arrayMessage);
$channel_send->basic_publish($msg, '', $MQ_QUEUE_BROKER_DB_NAME);
$channel_receive->basic_consume($MQ_QUEUE_DB_BROKER_NAME, '', false, true, false, false, $callback);

try {
    $channel_receive->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

?>
