#!/bin/php
<?php
require_once('rabbitMQLib.inc');

$config = parse_ini_file('db_mysql.ini');

$db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);

// TODO
// replace sql queries with pdo because thats safer in php
// replace ->query with ->prepare, ->bind_param, ->execute to allow robust error checking
// sessions and stuff

function doLogin($username,$password)
{
  global $db_conn;
  $response_str = "Success";
  $query = "SELECT username, password FROM users WHERE username='$username'";
  $result = $db_conn->query($query);
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

function doRegister($username,$password)
{
  global $db_conn;
  $query = "SELECT username FROM users WHERE username='$username'";
  $result = $db_conn->query($query);
  if ($result->num_rows != 0) {
      return array(
          "status" => "failed",
          "message" => "User exists"
      );
  }
  echo $username . " " . $password . "\n";
  $query = "INSERT INTO users VALUES ('$username','$password');";
  // need to assert query successfully executed here
  $result = $db_conn->query($query);
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
    case "register":
      return doRegister($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }

  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("db_server.ini","db_server");
$server->process_requests('requestProcessor');

?>
