#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

function doLogin($username,$password)
{
    $client = new rabbitMQClient("broker_client.ini", "broker");
    $req = array();
    $req["type"] = "login";
    $req["username"] = $username;
    $req["password"] = $password;
    $response = $client->send_request($req); 
    echo "Received response from db: \n";
    return $response;
}

function doRegister($username,$password)
{
    $client = new rabbitMQClient("broker_client.ini", "broker");
    $req = array();
    $req["type"] = "register";
    $req["username"] = $username;
    $req["password"] = $password;
    $response = $client->send_request($req); 
    echo "Received response from db: \n";
    return $response;
}

function doValidate($session)
{
    return false;
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "register":
        return doRegister($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("broker_server.ini","broker_server");
$server->process_requests('requestProcessor');
?>

