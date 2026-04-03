#!/bin/php
<?php
include "rabbitMQLib.inc";

function pushBundle($archive_path)
{
    $result_code = 0;
    $output = array();
    $dirname = dirname($archive_path);
    echo $dirname . "\n";
    exec("tar -C '$dirname' -xvf '$archive_path'", $output, $result_code);
    if ($result_code != 0) {
        echo "Could not extract bundle\n";
        return array(
            "status" => "failed",
            "response" => "Could not extract bundle"
        );
    }
    $info_ini = parse_ini_file("$dirname/info.ini", false);
    $run_script_path = "$dirname/files/run.sh";
    if (!file_exists($run_script_path)) {
        return array(
            "status" => "failed",
            "response" => "Files is missing run.sh"
        );
    }
    exec("chmod +x '$run_script_path'", $output, $result_code);
    if ($result_code != 0) {
        return array(
            "status" => "failed",
            "response" => $output
        );
    }
    exec($run_script_path, $output, $result_code);
    if ($result_code != 0) {
        return array(
            "status" => "failed",
            "response" => $output
        );
    }
    return array(
        "status" => "success",
        "response" => "hello from web"
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
            return pushBundle($request["archive_path"]);
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
