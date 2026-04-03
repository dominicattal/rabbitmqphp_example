#!/bin/php
<?php
include "rabbitMQLib.inc";

function pushBundle($target, $basename)
{
    $client = new rabbitMQClient("deploy_client.ini", "dev_web_listen_queue", "dev_web_listen");
    $request = array();
    $request['type'] = "push";
    $response = $client->send_request($request);
    unset($client);
    return array(
        "status" => "success",
        "version" => 0,
        "response" => $response
    );
}

function rollbackBundle($target, $bundle_name, $version)
{
    return array("status" => "not implemented yet");
}

function listBundles($type)
{
    // list all of the bundles available for a type like web, db, or data
    return array("status" => "not implemented yet");
}

function listBundleVersions($bundle_name)
{
    // should return all versions of a bundle
    return array("status" => "not implemented yet");
}

function listCurrentBundles($target)
{
    // should return all the currently installed bundles on a target
    return array("status" => "not implemented yet");
}

function requestProcessor($request)
{
    var_dump($request);
    switch ($request["type"]) {
        case "push":
            return pushBundle($request["target"], $request["basename"]);
        case "rollback":
            return rollbackBundle($request["target"], $request["bundle_name"], $request["version"]);
        case "list_bundles":
            return listBundles($request["type"]);
        case "list_bundle_versions":
            return listBundles($request["bundle_name"]);
        case "list_current_bundles":
            return listCurrentBundles($request["target"]);
    }
    return array("failed" => "Unrecognized type");
}

$server = new rabbitMQServer("deploy_server.ini");
$server->process_requests('requestProcessor');
?>
