<?php
require_once('../rabbitMQLib.inc');
include('../log.inc');
$client = new rabbitMQClient("../web_client.ini", "db_listen_queue", "db_listen");

$request = array();
$request['type'] = "get_all_reviews_for_user";
$request['username'] = $_POST["username"];
$response = $client->send_request($request);
if ($response == false){
	maddLog("all reviews request failed in web");
	return array("status"=>"failed");
}
echo json_encode($response);
?>
