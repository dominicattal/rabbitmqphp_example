#!/bin/php
<?php
include "rabbitMQLib.inc";

$clusters = parse_ini_file("clusters.ini", false);

$queue_map = array();
$queue_map["dev"]["web"]["queue_name"] = "dev_web_listen_queue";
$queue_map["dev"]["web"]["routing_key"] = "dev_web_listen";
$queue_map["dev"]["db"]["queue_name"] = "dev_db_listen_queue";
$queue_map["dev"]["db"]["routing_key"] = "dev_db_listen";
$queue_map["dev"]["data"]["queue_name"] = "dev_data_listen_queue";
$queue_map["dev"]["data"]["routing_key"] = "dev_data_listen";
$queue_map["qa"]["web"]["queue_name"] = "qa_web_listen_queue";
$queue_map["qa"]["web"]["routing_key"] = "qa_web_listen";
$queue_map["qa"]["db"]["queue_name"] = "qa_db_listen_queue";
$queue_map["qa"]["db"]["routing_key"] = "qa_db_listen";
$queue_map["qa"]["data"]["queue_name"] = "qa_data_listen_queue";
$queue_map["qa"]["data"]["routing_key"] = "qa_data_listen";
$queue_map["data"]["web"]["queue_name"] = "data_web_listen_queue";
$queue_map["data"]["web"]["routing_key"] = "data_web_listen";
$queue_map["data"]["db"]["queue_name"] = "data_db_listen_queue";
$queue_map["data"]["db"]["routing_key"] = "data_db_listen";
$queue_map["data"]["data"]["queue_name"] = "data_data_listen_queue";
$queue_map["data"]["data"]["routing_key"] = "data_data_listen";

function pushBundle($target, $archive_path)
{
    var_dump($target);
    var_dump($archive_path);
    global $queue_map, $clusters;
    $result_code = 0;
    $output = array();
    $dirname = dirname($archive_path);$output = array();
    exec("tar -C '$dirname' -vf '$archive_path' -x info.ini", $output, $result_code);
    if ($result_code != 0) {
        echo "Could not extract bundle\n";
        return array(
            "status" => "failed",
            "response" => "Could not extract bundle"
        );
    }
    $info_ini = parse_ini_file("$dirname/info.ini", false);
    $type = $info_ini["BUNDLE_TYPE"];

    $pfx = strtoupper("${target}_${type}");
    $hostname = $clusters["${pfx}_HOST"];
    $username = $clusters["${pfx}_USER"];
    $remote_path = $archive_path;
	
    exec("scp '$archive_path' scp://$username@$hostname/$remote_path", $output, $result_code);
    if ($result_code != 0) {
        echo "SCP Failed\n";
        return array(
            "status" => "failed",
            "response" => "Scp failed"
        );
    }
    
  

    $queue_name = $queue_map[$target][$type]["queue_name"];
    $routing_key = $queue_map[$target][$type]["routing_key"];
    $client = new rabbitMQClient("deploy_client.ini", $queue_name, $routing_key);
    $request = array();
    $request['type'] = "push";
    $request['archive_path'] = $remote_path;
    $response = $client->send_request($request);
    unset($client);
    if (!isset($response["status"]) || $response["status"] != "success") {
        return array(
            "status" => "failed",
            "response" => $response
        );
    }
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

function test($target, $archive_path)
{
   //Finish this command
   //Get the bundle name
   //Find that bundle in tmp/
   //Move the bundle to bundle/
   //Delete from tmp/
   //Make a DB entry for the bundle
   
   echo "Doing test!\n";
   echo "archive path is: $archive_path\n";
   
   $output = array();
   $result_code = 0;
   $dirname = dirname($archive_path);
   echo "Dirname is: $dirname\n";
   
   exec("tar -C '$dirname' -xvf '$archive_path'",$output,$result_code);
   if ($result_code != 0) 
   {
       echo "Could not extract bundle\n";
       return array(
           "status" => "failed",
            "response" => "Could not extract bundle"
        );
   }
    
   var_dump($output);
    
   //Extract the info.ini info: the Version + Type
   $info_ini = parse_ini_file("$dirname/info.ini", false);
   $type = $info_ini["BUNDLE_TYPE"];
   $version = $info_ini["BUNDLE_VERSION"];
   
   echo "Type of INI is: $type\n";
   echo "Version of INI is: $version\n";
   
    
   $tarName = basename($archive_path);
   echo "Tar file name: $tarName\n";
   
   $output2 = array();
   $cmd = __DIR__ . "/removeFromTmp.sh " . $tarName . " " .$type . " " . $version; 
   exec($cmd. " 2>&1"   , $output2, $return_code);  

   echo "Post test!\n";
   var_dump($output2);
   
   return array("status" => "not implemented yet");
}

function requestProcessor($request)
{
    var_dump($request);
    switch ($request["type"]) {
        case "push":
            return pushBundle($request["target"], $request["archive_path"]);
        case "rollback":
            return rollbackBundle($request["target"], $request["bundle_name"], $request["version"]);
        case "list_bundles":
            return listBundles($request["type"]);
        case "list_bundle_versions":
            return listBundles($request["bundle_name"]);
        case "list_current_bundles":
            return listCurrentBundles($request["target"]);
        case "test":
            return test($request["target"], $request["archive_path"]);
    }
    return array("failed" => "Unrecognized type");
}

$server = new rabbitMQServer("deploy_server.ini");
$server->process_requests('requestProcessor');
?>
