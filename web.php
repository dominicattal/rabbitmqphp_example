#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("web_client.ini", "db_web_queue", "db_web");

$request = array();
//$request['type'] = "movie";
//$request['id'] = 1266798;
$request['type'] = "popular";
$request['count'] = 10;
$response = $client->send_request($request);
//$response = $client->publish($request);
var_dump($response);
