<?php
require_once('../rabbitMQLib.inc');
include('../log.inc');
$client = new rabbitMQClient("../web_client.ini", "db_listen_queue", "db_listen");
$request = array();
$request['type'] = "achievement";
$request['username'] = $_POST["username"];
$response = $client->send_request($request);
if ($response == false){
	maddLog("achievement request failed in web");
	return array("status"=>"failed");
}
echo json_encode($response);
?>
