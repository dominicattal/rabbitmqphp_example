<?php
if (!isset($_POST)) {
  trigger_error("Missing post data", E_USER_WARNING);
  goto fail;
}
$username = $_POST["username"];
if (!isset($username)) {
  trigger_error("Missing username", E_USER_WARNING);
  goto fail;
}
$password = htmlspecialchars($_POST["password"]);
if (!isset($password)) {
  trigger_error("Missing password", E_USER_WARNING);
  goto fail;
}

require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$config = parse_ini_file('../config.ini');

$EXCHANGE = '';

$MQ_BROKER_HOST = $config["MQ_BROKER_HOST"];
$MQ_BROKER_PORT = $config["MQ_BROKER_PORT"];
$MQ_BROKER_USER = $config["MQ_BROKER_USER"];
$MQ_BROKER_PASS = $config["MQ_BROKER_PASS"];
$MQ_QUEUE_WEB_BROKER_NAME = $config["MQ_QUEUE_WEB_BROKER_NAME"];

$connection_send = new AMQPStreamConnection($MQ_BROKER_HOST, $MQ_BROKER_PORT, $MQ_BROKER_USER, $MQ_BROKER_PASS);
$channel_send = $connection_send->channel();
list($queue_name, ,) = $channel_send->queue_declare("", false, false, true);

$server_responded = false;
$server_response = "";
$correlation_id = uniqid();

$callback = function (AMQPMessage $msg_in) {
  global $server_responded, $server_response, $correlation_id;
  if ($msg_in->get('correlation_id') === $correlation_id)
    return;
  $server_response = $msg_in->getBody();
  $server_responded = true;
};

$arr = array();
$arr["username"] = $username;
$arr["password"] = $password;
$arrayMessage = json_encode($arr);

$msg = new AMQPMessage($arrayMessage, [
  'reply_to' => $queue_name, 
  'correlation_id' => uniqid(),
  'content_type' => "application/json"
]);
$channel_send->basic_publish($msg, $EXCHANGE, $MQ_QUEUE_WEB_BROKER_NAME);
$channel_send->basic_consume($queue_name, '', false, true, false, false, $callback);
while (!$server_responded) {
  $channel_send->wait();
}
echo $server_response;


header("Location: home.php");
die();

fail:
header("Location: login.php");
die();
?>
