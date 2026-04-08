<?php
require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient("../web_client.ini", "db_web_queue", "db_web");

$request = array();
$request['type'] = "get_all_reviews_for_user";
$request['username'] = $_POST["username"];
$response = $client->send_request($request);

echo json_encode($response);
?>
