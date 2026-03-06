<?php
session_start();
require_once('../rabbitMQLib.inc');

$username = $_SESSION['username'] ?? null;
if (!$username) {
    echo json_encode(["status" => "error", "message" => "Login required"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client = new rabbitMQClient("web_client.ini", "db_queue", "db");
    
    $request = [
        'type' => "add_watchlist",
        'username' => $username,
        'movie_id' => $_POST['movie_id'],
        'movie_name' => $_POST['movie_name']
        'release_date' => $_POST['release_date']
    ];

    $response = $client->send_request($request);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
