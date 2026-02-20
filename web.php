#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("web_client.ini","web_client");

$request = array();
$request['type'] = "login";
$request['username'] = "test";
$request['password'] = "test";
$request['message'] = "HI";
$response = $client->send_request($request);
//$response = $client->publish($request);
var_dump($response);


