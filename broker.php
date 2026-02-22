#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

function message_log($message)
{
    var_dump($message);
}

$server = new rabbitMQServer("broker_server.ini","broker_server");
$server->process_requests('message_log');
?>

