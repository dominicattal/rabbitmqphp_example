#!/bin/php
<?php
require_once('rabbitMQLib.inc');

$config = parse_ini_file('config.ini');

$db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);

function doLogin($username,$password)
{
  global $db_conn;
  $query = "SELECT username, password FROM user WHERE username='$username'";
  $result = $db_conn->query($query);
  $response_str = "Success";
  if ($result->num_rows == 0) {
    return array(
      "status" => "failed",
      "message" => "User not found"
    );
  }
  $row = $result->fetch_assoc();
  if ($password !== $row["password"]) {
    return array(
      "status" => "failed",
      "message" => "Invalid password"
    );
  }
  return array(
    "status" => "success",
    "message" => ""
  );
}

function doValidate($session)
{
    return array(
        "status" => "failed",
        "message" => "not implemented"
    );
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }

  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("db_server.ini","db_server");
$server->process_requests('requestProcessor');

?>
