#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

//$client = new rabbitMQClient("web_client.ini", "db_queue", "db");
$client = new rabbitMQClient("web_client.ini", "data_queue", "data");

$request = array();
$request['type'] = "movie";
$request['id'] = 1290821;
$response = $client->send_request($request);
//$response = $client->publish($request);
var_dump($response);
