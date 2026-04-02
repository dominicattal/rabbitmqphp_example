#!/bin/php
<?php
include "rabbitMQLib.inc";

if (count($argv) != 3) {
    echo "Usage: deploy.php [dev/qa/prod] [bundle]\n";
    echo "Consult README.md\n";
    exit(1);
}

$client = new rabbitMQClient("deploy_client.ini", "main_deploy_queue", "main_deploy");
$request = array();
$request['type'] = "push";
$request['zip_path'] = $argv[2];
$response = $client->send_request($request);
var_dump($response);

?>
