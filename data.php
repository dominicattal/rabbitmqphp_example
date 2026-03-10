#!/bin/php
<?php
require_once('rabbitMQLib.inc');

$ini = parse_ini_file(".api.ini", false);
$key = $ini["TMDB_KEY"];

function getRequest($url)
{
    global $key;
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $key",
        "accept: application/json"
      ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function getMovie($id)
{
    $encoded_json = getRequest("https://api.themoviedb.org/3/movie/$id?language=en-US");
    return json_decode($encoded_json, true);
}

function getPopularMovies($count)
{
    $encoded_json = getRequest('https://api.themoviedb.org/3/movie/popular?language=en-US&page=1');
    return json_decode($encoded_json, true);
}

function getUpcomingMovies()
{
    $encoded_json = getRequest('https://api.themoviedb.org/3/movie/upcoming?language=en-US&page=1');
    return json_decode($encoded_json, true);
}

function getGenres()
{
    $encoded_json = getRequest('https://api.themoviedb.org/3/genre/movie/list');
    return json_decode($encoded_json, true);
}

function getPopularInGenre($genre_id)
{
    $encoded_json = getRequest("https://api.themoviedb.org/3/discover/movie?language=en-US&with_genres=$genre_id");
    return json_decode($encoded_json, true);
}
function getHigherLower($count){
	$encoded_json=getRequest("https://api.themoviedb.org/3/discover/movie");
	return json_decode($encoded_json,true);
}

function requestProcessor($request)
{
    echo "Printing request:\n";
    var_dump($request);
    switch ($request["type"]) {
        case "popular":
            return getPopularMovies($request["count"]);
        case "movie":
	        return getMovie($request["id"]);
	case "upcoming":
            return getUpcomingMovies();
        case "genres":
            return getGenres();
        case "popular_in_genre":
            return getPopularInGenre($request["genre_id"]);
	case "higherlower":
		return getHigherLower($request["count"]);
    }
    return array("status" => "failed", "message" => "unrecognized type");
}

$server = new rabbitMQServer("data_server.ini");
$server->process_requests('requestProcessor');

