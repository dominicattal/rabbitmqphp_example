<?php
session_start(); // Crucial: This prevents the automatic logout
require_once('../rabbitMQLib.inc');

$username = $_SESSION['username'] ?? null;

// If the session isn't found, we can't add to the list
if (!$username) {
    header("Location: login.html?error=session_expired");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['movie_id'];
    $movieName = $_POST['movie_name'];

    $client = new rabbitMQClient("web_client.ini", "db_queue", "db");
    
    $request = array();
    $request['type'] = "add_watchlist"; // This must match your db.php switch case
    $request['username'] = $username;
    $request['movie_id'] = $movieId;
    $request['movie_name'] = $movieName;

    $response = $client->send_request($request);

    // Redirect back to the movie details or the watchlist page
    header("Location: watchlist.php");
    exit();
}
?>
