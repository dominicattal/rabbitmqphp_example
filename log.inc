<?php
require_once('rabbitMQLib.inc');
$log_client_ini = parse_ini_file("log_client.ini");
function maddLog($msg)
{
    global $log_client_ini;
    $client = new rabbitMQClient("log_client.ini", "log_listen_queue", "#");
    $log_message = array(
        "message" => $msg,
        "time" => time(),
        "backtrace" => debug_backtrace()
    );
    $client->HOST = $log_client_ini["MQ_WEB_HOST"];
    $client->publish($log_message);
    $client->HOST = $log_client_ini["MQ_DB_HOST"];
    $client->publish($log_message);
    $client->HOST = $log_client_ini["MQ_DATA_HOST"];
    $client->publish($log_message);
}
?>
