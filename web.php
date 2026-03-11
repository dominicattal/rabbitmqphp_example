#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("web_client.ini", "db_web_queue", "db_web");

$request = array();

//$request['type'] = "register";
//$request['username'] = "test1";
//$request['email'] = "it490madd@gmail.com";
//$request['password'] = "12345";

//$request['type'] = "movie";
//$request['id'] = 1266798;

//$request['type'] = "popular";
//$request['count'] = 10;

//$request['type'] = "add_watchlist";
//$request['movie_id'] = 1266798;
//$request['movie_name'] = "blah blah";

//$request['type'] = "recommend";
//$request['username'] = "test_recommend";

//$request['type'] = "get_all_reviews_for_user";
//$request['username'] = "test_recommend";

$request['type'] = "upcoming";

$response = $client->send_request($request);
//$response = $client->publish($request);
var_dump($response);
