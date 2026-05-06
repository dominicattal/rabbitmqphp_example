<script>
<?php
var_dump($_POST);
require_once('../rabbitMQLib.inc');
include('../log.inc');
$client = new rabbitMQClient("../web_client.ini", "db_listen_queue", "db_listen");

$request = array();
$request['type'] = "review_review";
$request['username'] = $_POST['username'];
$request['review_id'] = $_POST['review_id'];
$request['status'] = $_POST['status'];

$response = $client->send_request($request);
if ($response == false){
	maddLog("review review request failed in web");
	return array("status"=>"failed");
}
var_dump($response);
?>
/script>
