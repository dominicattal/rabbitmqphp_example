#!/bin/php
<?php
require_once __DIR__."/vendor/autoload.php";
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$config = parse_ini_file('testdb.ini');

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
$MYSQL_HOST = $config["MYSQL_HOST"];
$MYSQL_USER = $config["MYSQL_USER"];
$MYSQL_PASS = $config["MYSQL_PASS"];
$MYSQL_DB = $config["MYSQL_DB"];

$db_conn = new mysqli($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS,$MYSQL_DB);

$connection_recv = new AMQPStreamConnection($MQ_DB_HOST, $MQ_DB_PORT, $MQ_DB_USER, $MQ_DB_PASS);
$channel_recv = $connection_recv->channel();
$channel_recv->queue_declare($MQ_QUEUE_BROKER_DB_NAME, false, false, false, false);

$connection_send = new AMQPStreamConnection($MQ_BROKER_HOST, $MQ_BROKER_PORT, $MQ_BROKER_USER, $MQ_BROKER_PASS);
$channel_send = $connection_send->channel();
$channel_send->queue_declare($MQ_QUEUE_DB_BROKER_NAME, false, false, false, false);

$callback = function (AMQPMessage $msg_in) {
  global $db_conn, $channel_send;
  echo ' [x] Received ', $msg_in->getBody(), "\n";
  $msg_decoded = json_decode($msg_in->getBody(), true);
  $query = "SELECT username, password FROM user WHERE username='$msg_decoded[username]'";
  $result = $db_conn->query($query);
  if ($result->num_rows == 0) {
    $msg_out = new AMQPMessage('User not found');
    goto send;
  }
  $row = $result->fetch_assoc();
  if (strcmp($msg_decoded["password"], $row["password"]) != 0) {
    $msg_out = new AMQPMessage('Invalid password');
    goto send;
  }
  $msg_out = new AMQPMessage('Success');
send:
  $channel_send->basic_publish($msg_out, '', $MQ_QUEUE_DB_BROKER_NAME);
};

$channel_recv->basic_consume($MQ_QUEUE_BROKER_DB_NAME, '', false, true, false, false, $callback);
try {
  $channel_recv->consume();
} catch (\Throwable $exception) {
  echo $exception->getMessage();
}
$channel_recv->close();
$connection->close();
$db_conn->close();
?>
