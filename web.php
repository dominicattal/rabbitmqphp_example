#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("web_client.ini", "data_queue", "data");

$request = array();
$request['type'] = "login";
$request['username'] = "test";
$request['password'] = "test";
$response = $client->send_request($request);
//$response = $client->publish($request);
var_dump($response);


