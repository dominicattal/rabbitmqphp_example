#!/bin/php
<?php
require_once __DIR__."/vendor/autoload.php";
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$db_conn = new mysqli('localhost','dom','pass','test');

$connection_recv = new AMQPStreamConnection("localhost", 5672, "dom", "attal");
$channel_recv = $connection_recv->channel();
$channel_recv->queue_declare('queue_broker_db', false, false, false, false);

$connection_send = new AMQPStreamConnection("100.115.164.18", 5672, "test", "test");
$channel_send = $connection_send->channel();
$channel_send->queue_declare('queue_db_broker', false, false, false, false);

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
  $channel_send->basic_publish($msg_out, '', 'queue_db_broker');
};

$channel_recv->basic_consume('queue_broker_db', '', false, true, false, false, $callback);
try {
  $channel_recv->consume();
} catch (\Throwable $exception) {
  echo $exception->getMessage();
}
$channel_recv->close();
$connection->close();
$db_conn->close();
?>
