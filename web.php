#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("web_client.ini","web_client");

$request = array();
$request['type'] = "register";
$request['username'] = "dom";
$request['password'] = "attal";
$response = $client->send_request($request);
//$response = $client->publish($request);
var_dump($response);


