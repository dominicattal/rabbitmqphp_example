#!/bin/php
<?php
include "rabbitMQLib.inc";

if (count($argv) == 1) {
    echo "Usage: deploy/client.php [push/rollback/list/mark]\n";
    echo "Consult README.md\n";
    exit(1);
}
$function = $argv[1];
if (!in_array($function, ["push","rollback","list","mark"])) {
    echo "Wrong function type\n";
    echo "Usage: deploy/client.php [push/rollback/list/mark]\n";
    echo "Consult README.md\n";
    exit(1);
}

function validateTarget($target)
{
    return $target == "dev" || $target == "qa" || $target == "prod";
}

if ($function == "push") {
    echo "Pushing\n";
    if (count($argv) != 4) {
        echo "Wrong number of arguments\n";
        echo "Usage: client.php push [dev/qa/prod] [path_to_bundle]\n";
        echo "Consult README.md\n";
        exit(1);
    }
    $target = $argv[2];
    if (!validateTarget($target)) {
        echo "Invalid target $target\n";
        echo "Usage: client.php push [dev/qa/prod] [path_to_bundle]\n";
        echo "Consult README.md\n";
        exit(1);
    }
    $path = $argv[3];
    $output = array();
    $result_code = 0;
    exec("deploy/bundlify.sh $path", $output, $result_code);
    foreach ($output as $line) {
        echo "$line\n";
    }
    if ($result_code != 0) {
        echo "Could not compress bundle\n";
        exit(1);
    }
    $archive_path=$output[0];
    echo "Compressed bundle to $archive_path\n";
    $ini = parse_ini_file("main_client.ini", false);

    $basename = basename($archive_path);
    $remote_path = "/tmp/$basename";
    exec("scp '$archive_path' scp://$ini[DEPLOY_USER]@$ini[DEPLOY_HOST]/$remote_path", $output, $result_code);
    if ($result_code != 0){
        echo "Scp failed:\n";
        var_dump($output);
        exit(1);
    }

    $client = new rabbitMQClient("main_client.ini", "deploy_listen_queue", "deploy_listen");
    $request = array();
    $request['type'] = "push";
    $request['target'] = $target;
    $request['archive_path'] = $remote_path;
    $response = $client->send_request($request);
    echo "created $response[bundle_name] v$response[version]\n";

} else if ($function == "rollback") {
    echo "Rolling back\n";
    if (count($argv) != 4) {
        echo "Wrong number of arguments\n";
        echo "Usage: client.php rollback [dev/qa/prod] [bundle_name]\n";
        echo "Consult README.md\n";
        exit(1);
    }
    $target = $argv[2];
    if (!validateTarget($target)) {
        echo "Invalid target\n";
        echo "Usage: client.php rollback [dev/qa/prod] [bundle_name]\n";
        echo "Consult README.md\n";
        exit(1);
    }
    $client = new rabbitMQClient("main_client.ini", "deploy_listen_queue", "deploy_listen");
    $request = array();
    $request['type'] = "rollback";
    $request['target'] = $target;
    $request['bundle_name'] = $argv[3];
    $response = $client->send_request($request);
    var_dump($response);
} else if ($function == "list") {
    $client = new rabbitMQClient("main_client.ini", "deploy_listen_queue", "deploy_listen");
    $request = array();
    $request['type'] = "list_bundles";
    $response = $client->send_request($request);
    foreach ($response as $bundle) {
        echo "$bundle\n";
    }
} else if ($function == "mark") {
    if (count($argv) != 5) {
        echo "Wrong number of arguments\n";
        echo "Usage: client.php mark [bundle_name] [version] [good/bad/new]\n";
        echo "Consult README.md\n";
        exit(1);
    }
    if (!is_numeric($argv[3])) {
        echo "Version must be a number\n";
        echo "Usage: client.php mark [bundle_name] [version] [good/bad/new]\n";
        echo "Consult README.md\n";
        exit(1);
    }
    $status = $argv[4];
    if (!in_array($status, ["good","bad","new"])) {
        echo "Invalid status $status\n";
        echo "Usage: client.php mark [bundle_name] [version] [good/bad/new]\n";
        echo "Consult README.md\n";
        exit(1);
    }
    $client = new rabbitMQClient("main_client.ini", "deploy_listen_queue", "deploy_listen");
    $request = array();
    $request['type'] = "mark";
    $request['bundle_name'] = $argv[2];
    $request['version'] = $argv[3];
    $request['status'] = $status;
    $response = $client->send_request($request);
    var_dump($response);
}

?>
