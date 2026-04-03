#!/bin/php
<?php
include "rabbitMQLib.inc";

function pushBundle($basename)
{
    return array(
        "status" => "success",
        "hello" => "world"
    );
}

function rollbackBundle($bundle_name, $version)
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

function listCurrentBundles()
{
    // should return all the currently installed bundles on a target
    return array("status" => "not implemented yet");
}

function requestProcessor($request)
{
    var_dump($request);
    switch ($request["type"]) {
        case "push":
            return pushBundle($request["basename"]);
        case "rollback":
            return rollbackBundle($request["bundle_name"], $request["version"]);
        case "list_bundles":
            return listBundles();
        case "list_bundle_versions":
            return listBundles($request["bundle_name"]);
        case "list_current_bundles":
            return listCurrentBundles();
    }
    return array("failed" => "Unrecognized type");
}

$server = new rabbitMQServer("dev_web_server.ini");
$server->process_requests('requestProcessor');
?>
