#!/bin/php
<?php
include "rabbitMQLib.inc";
if (count($argv) != 3) {
    echo "Usage: deploy.php [dev/qa/prod] [bundle]\n";
    echo "Consult README.md\n";
    exit(1);
}
$target = $argv[1];
if ($target != "dev" && $target != "qa" && $target != "prod") {
    echo "Usage: deploy.php [dev/qa/prod] [bundle]\n";
    echo "Consult README.md\n";
    exit(1);
}

$path = realpath($argv[2]);
$basename = basename($path);
$ini = parse_ini_file("bundle_client.ini", false);
$conn = ssh2_connect($ini["DEPLOY_HOST"], 22);
if (!$conn) {
    echo "Could not connect\n";
    exit(1);
}
$res = ssh2_auth_password($conn, $ini["DEPLOY_USER"], $ini["DEPLOY_PASS"]);
if (!$res) {
    echo "User/Password did not work\n";
    exit(1);
}
$res = ssh2_scp_send($conn, $path, "/home/$ini[DEPLOY_USER]/$basename", 0777);
if (!$res) {
    echo "Could not send scp\n";
    exit(1);
}

$client = new rabbitMQClient("bundle_client.ini", "deploy_listen_queue", "deploy_listen");
$request = array();
$request['type'] = "push";
$request['target'] = $target;
$request['basename'] = $basename;
$response = $client->send_request($request);
var_dump($response);
?>
