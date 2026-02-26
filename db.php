#!/bin/php
<?php
require_once('rabbitMQLib.inc');

$config = parse_ini_file('db_mysql.ini');

$db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);

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
  $arr = doValidate($username);
  return array(
    "status" => "success",
    "key" => $arr["key"],
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
  $query = "INSERT INTO users VALUES ('$username','$password');";
  // need to assert query successfully executed here
  $result = $db_conn->query($query);
  $arr = doValidate($username);
  return array(
    "status" => "success",
    "key" => $arr["key"],
    "message" => ""
  );
}

function doValidate($username)
{
//Making the validations update to make a sessionKey -ME
    global $db_conn;
    $query = "SELECT username from validations where username='$username'";
    $result = $db_conn->query($query);
    $key = "";

   if ($result->num_rows == 0)
   {
	//Good means no former sessionKey
	$key = bin2hex(random_bytes(10));
	$now = time();
    $expTime = $now + 300;
	$query = "INSERT INTO validations (username,      		
	sessionKey, createdAt, expiresAt)
        VALUES ('$username', '$key', $now, $expTime);";
	
	$result = $db_conn->query($query);
	echo "Hopefully added the validation!";
   }
   else
   {
	//Need to clear the current key
	$query = "DELETE FROM validations
WHERE username = $username";

	$key = bin2hex(random_bytes(10));
	$now = time();
        $expTime = $now + 300;
	$query = "INSERT INTO validations (username,      		
	sessionKey, createdAt, expiresAt)
        VALUES ('$username', '$key', $now, $expTime);";
	
	$result = $db_conn->query($query);

	echo "Hopefully cleared then added the validation!";
   }
  return array(
    "status" => "success",
    "key" => $key,
    "message" => ""
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
      return doValidate($request['username']);
  }

  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("db_server.ini","db_server");
$server->process_requests('requestProcessor');

?>
