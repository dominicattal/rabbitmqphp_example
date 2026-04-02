#!/bin/php
<?php
include "rabbitMQLib.inc";

function handlePush($zip_path)
{
    return array("status" => "success");
}

function requestProcessor($request)
{
    var_dump($request);
    switch ($request["type"]) {
        case "push":
            return handlePush($request["zip_path"]);
    }
    return array("failed" => "Unrecognized type");
}

$server = new rabbitMQServer("deploy_server.ini");
$server->process_requests('requestProcessor');
?>
