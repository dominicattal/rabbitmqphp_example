#!/bin/php
<?php
require_once('rabbitMQLib.inc');
require_once('email.php');

$client = new rabbitMQClient("web_client.ini", "db_web_queue", "db_web");

$request = array();
$request['type'] = "recommend";
$request['username'] = "test_recommend";

$response = $client->send_request($request);
$movie = $response["results"][0];
$title = $movie["title"];
$overview = $movie["overview"];
$poster_img_url = $movie["poster_img_url"];

$body = "
    <h2>$title</h2>
    <p>$overview</p>
    <img src=$poster_img_url alt=$title></img>
";

var_dump($movie);

sendEmail("it490madd@gmail.com", "Watch $title Now", $body);

?>
