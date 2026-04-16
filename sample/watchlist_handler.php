<?php
require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("../web_client.ini", "db_listen_queue", "db_listen");
$request = array();
$request['type'] = "watchlist";
$request['username'] = $_POST["username"];
$response = $client->send_request($request);
echo json_encode($response);
?>
