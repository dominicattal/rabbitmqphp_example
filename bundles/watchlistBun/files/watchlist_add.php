<?php
require_once('../rabbitMQLib.inc');
include('../log.inc');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['movie_id'];
    $movieName = $_POST['movie_name'];

    $client = new rabbitMQClient("web_client.ini", "db_listen_queue", "db_listen");
    $request = [
        'type' => "add_watchlist",
        'username' => $_POST["username"],
        'movie_id' => $_POST['movie_id'],
        'movie_name' => $_POST['movie_name'],
        'release_date' => $_POST['release_date'] ?? 'TBD'
    ];

    $response = $client->send_request($request);
	if ($response == false){
	maddLog("watchlist add request failed in web");
	return array("status"=>"failed");
}
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

$response = array(
    "status" => "failed",
    "message" => "Not post method"
);
echo json_encode($response);
