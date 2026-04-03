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

$path = $argv[2];
$output = array();
$result_code = 0;
exec("deploy/bundlify.sh $path", $output, $result_code);
foreach ($output as $line) {
    echo $line . "\n";
}
if ($result_code != 0) {
    echo "Could not compress bundle\n";
    exit(1);
}
$archive_path=$output[0];
echo "Compressed bundle to $archive_path\n";
$ini = parse_ini_file("bundle_client.ini", false);

$basename = basename($archive_path);
$remote_path = "/tmp/$basename";
exec("scp '$archive_path' scp://$ini[DEPLOY_USER]@$ini[DEPLOY_HOST]/$remote_path", $output, $result_code);
if ($result_code != 0){
    echo "Scp failed:\n";
    var_dump($output);
    exit(1);
}

$client = new rabbitMQClient("bundle_client.ini", "deploy_listen_queue", "deploy_listen");
$request = array();
$request['type'] = "push";
$request['target'] = $target;
$request['archive_path'] = $remote_path;
$response = $client->send_request($request);
var_dump($response);
?>
