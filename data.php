#!/bin/php
<?php
require_once('rabbitMQLib.inc');

function requestProcessor($request)
{
    var_dump($request);
    return array("received" => "true");
}

$server = new rabbitMQServer("data_server.ini","web_queue","web");
$server->process_requests('requestProcessor');

