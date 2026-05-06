#!/bin/php
<?php
require_once('rabbitMQLib.inc');

$log_server_ini = parse_ini_file("log_server.ini");
$log_file_path = $log_server_ini["LOG_PATH"];

function requestProcessor($request)
{
    global $log_file_path;
    $string = json_encode($request) . "\n";
    file_put_contents($log_file_path, $string, FILE_APPEND);
}

$server = new rabbitMQServer("log_server.ini");
$server->process_requests('requestProcessor');
?>
