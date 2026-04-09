#!/bin/php
<?php
include "rabbitMQLib.inc";


$config = parse_ini_file('deploy_db.ini');
$db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);


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

function rollbackBundle($target, $bundle_name)
{
    global $db_conn;
    $query = "SELECT * FROM bundleList WHERE name='$bundle_name' LIMIT 1";
    $result = $db_conn->query($query);
    if ($result->num_rows == 0) {
        return array(
            "status" => "failed",
            "message" => "$bundle_name does not exist"
        );
    }
    $query = "SELECT * FROM bundleList WHERE name='$bundle_name' AND status='good' ORDER BY version DESC LIMIT 1";
    $result = $db_conn->query($query);
    if ($result->num_rows == 0) {
        return array(
            "status" => "failed",
            "message" => "no good version of bundle $bundle_name found"
        );
    }
    $row = $result->fetch_assoc();
    return pushBundle($target, $row["file_path"]);
}

function listBundles($type)
{
    // list all of the bundles available for a type like web, db, or data
    //Make function that updates a bundle's mark - Do after this
    
    global $db_conn;
    $query = "SELECT * FROM bundleList;";
    
    $result = $db_conn->query($query);
    
    if ($result->num_rows == 0)
    {
       echo "No results from DB for bundles!";
       $response = "No results from DB for bundles!";
      
       return array(
       "status" => "success",
       "response" =>$response
       );
    }
        $response=array();
        while ($row = $result->fetch_assoc()) 
        {
            
            $response[] = $row;
        }
        var_dump($response);
        return $response;
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
   //echo "Doing test!\n";
   //echo "archive path is: $archive_path\n";
   
   $output = array();
   $result_code = 0;
   $dirname = dirname($archive_path);
   //echo "Dirname is: $dirname\n";
   
   exec("tar -C '$dirname' -xvf '$archive_path'",$output,$result_code);
   if ($result_code != 0) 
   {
       echo "Could not extract bundle\n";
       return array(
           "status" => "failed",
            "response" => "Could not extract bundle"
        );
   }
    
   //var_dump($output);
    
   $info_ini = parse_ini_file("$dirname/info.ini", false);
   $type = $info_ini["BUNDLE_TYPE"];
   $version = $info_ini["BUNDLE_VERSION"];
   
   //echo "Type of INI is: $type\n";
   //echo "Version of INI is: $version\n";
   
    
   $tarName = basename($archive_path,".tar");
   //echo "Tar file name: $tarName\n";
   
   $output2 = array(); 
   exec(__DIR__ . "/removeFromTmp.sh " . $tarName . " " .$type . " " . $version. " 2>&1"   , $output2, $return_code);  

   //echo "Post test!\n";
   var_dump($output2);
   $newName = end($output2);
   $newName2 = "~/bundles/".$newName;
   
   //Now need to send the newly made db_1_bundle.tar to the DB server for storage?
   $state = "untested";
   global $db_conn;
   $query = "INSERT INTO bundleList (name, version, type, status, file_path) VALUES ('$newName','$version','$type','$state','$newName2');";
   
   $result = $db_conn->query($query);
   
   echo "Finished with pushing the bundle, moving it to ~/bundles, and storing it in the DB";
   
   return array("status" => "Bundle Recieved and stored!");
}

function markBundle($name_bundle, $version_bundle,$new_status)
{
   global $db_conn;
   $query = "SELECT * FROM bundleList where version = '$version_bundle' AND name = '$name_bundle';";
   $result = $db_conn->query($query);
    
   if ($result->num_rows == 0)
    {
       echo "No results from DB for bundles!\nCannot update what does not exist!";
       $response = "No results from DB for bundles!\nCannot update what does not exist!";
      
       return array(
       "status" => "failed",
       "response" =>$response
       );
    }
   
   $query = "UPDATE bundleList set status = '$new_status' where version = $version_bundle AND name = '$name_bundle';";
   return array("status" => "Bundle status updated!");
}

function requestProcessor($request)
{
//New, Good, Bad $tarName = basename($archive_path);
    var_dump($request);
    switch ($request["type"]) {
        case "push":
            return pushBundle($request["target"], $request["archive_path"]);
        case "rollback":
            return rollbackBundle($request["target"], $request["bundle_name"]);
        case "list_bundles":
            return listBundles($request["type"]);
        case "list_bundle_versions":
            return listBundles($request["bundle_name"]);
        case "list_current_bundles":
            return listCurrentBundles($request["target"]);
        case "test":
            return test($request["target"], $request["archive_path"]);
        case "mark":
            return update_status($request["name_bundle"],$request["version_bundle"],$request["new_status"]);
    }
    return array("failed" => "Unrecognized type");
}

$server = new rabbitMQServer("deploy_server.ini");
$server->process_requests('requestProcessor');
?>
