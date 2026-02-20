#!/bin/php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$config = parse_ini_file('config.ini');

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
$MQ_QUEUE_WEB_BROKER_NAME = $config["MQ_QUEUE_WEB_BROKER_NAME"];

$connection_send_db = new AMQPStreamConnection($MQ_DB_HOST, $MQ_DB_PORT, $MQ_DB_USER, $MQ_DB_PASS);
$channel_send_db = $connection_send_db->channel();
$channel_send_db->queue_declare($MQ_QUEUE_BROKER_DB_NAME, false, false, false, false);

$connection_receive_web = new AMQPStreamConnection($MQ_BROKER_HOST, $MQ_BROKER_PORT, $MQ_BROKER_USER, $MQ_BROKER_PASS);
$channel_receive_web = $connection_receive_web->channel();
$channel_receive_web->queue_declare($MQ_QUEUE_WEB_BROKER_NAME, false, false, false, false);

class RequestHandler
{
  public $correlation_id;
  public $response;
  public function __construct($correlation_id)
  {
    $this->correlation_id = $correlation_id;
    $this->response = "";
  }
  public function db_callback(AMQPMessage $db_msg)
  {
    global $db_requests;
    $correlation_id = $db_msg->get('correlation_id');
    if ($correlation_id !== $this->correlation_id)
      return;
    $this->response = $db_msg->getBody();
  }
}

$web_callback = function (AMQPMessage $web_req) {
  global $channel_send_db, $MQ_QUEUE_BROKER_DB_NAME;
  $correlation_id = $web_req->get('correlation_id');
  list($queue_name, ,) = $channel_send_db->queue_declare("", false, false, true);

  $req_handler = new RequestHandler($correlation_id);

  $db_req = new AMQPMessage($web_req->getBody() ,[
    'reply_to' => $queue_name, 
    'correlation_id' => $correlation_id,
    'content_type' => "application/json"
  ]);

  $channel_send_db->basic_publish($db_req, '', $MQ_QUEUE_BROKER_DB_NAME);
  $channel_send_db->basic_consume($queue_name, '', false, true, false, false, [ $req_handler, 'db_callback' ]);
  while (!$req_handler->response)
    $channel_send_db->wait();

  $web_response = new AMQPMessage(
    $req_handler->response,
    ['correlation_id' => $correlation_id]
  );

  $web_req->getChannel()->basic_publish($web_response, '', $web_req->get('reply_to'));

};

$channel_receive_web->basic_consume($MQ_QUEUE_WEB_BROKER_NAME, '', false, true, false, false, $web_callback);
try {
    $channel_receive_web->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

?>
