#!/bin/php
<?php
require_once('rabbitMQLib.inc');

$config = parse_ini_file('db_mysql.ini');

$db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);

function doLogin($username, $password)
{
  global $db_conn;
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

  echo "User logging in, validating!\n";

  //First need to check if the user has a valid session already, if yes kill it !!! - ME
  $query = "SELECT username FROM validations WHERE username='$username'";
  $result = $db_conn->query($query);
  
  if ($result->num_rows > 0) 
  {
    echo "User has an expired Key! Killing it!\n";
    $query = "DELETE FROM validations WHERE username = '$username'";
    $result = $db_conn->query($query);
  }

  $arr = doValidate($username);
  if (!isset($arr["status"]) || $arr["status"] != "success") {
      return array(
          "status" => "failed",
          "message" => "validation failed"
      );
  }

  return array(
    "status" => "success",
    "key" => $arr["key"],
    "message" => "Login Successful"
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
  $query = "INSERT INTO users (username, password) VALUES ('$username','$password');";
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
  echo "Trying a validation!\n";
    global $db_conn;
    $query = "SELECT username from validations where username='$username'";
    $result = $db_conn->query($query);
    $key = "";
    $timeToAdd=300;
	
   if ($result->num_rows == 0)
   {
    echo "No user sessions, creating one!\n";
    $key = bin2hex(random_bytes(10));
    $now = time();
    $expTime = $now + $timeToAdd;
    $query = "INSERT INTO validations (username, sessionKey, createdAt, expiresAt)
        VALUES ('$username', '$key', $now, $expTime);";
    $result = $db_conn->query($query);
   }
   else
   {
    //Check if the user is expired. If not clear the old time and give them a new one/new key - ME
    $query = "SELECT expiresAt FROM validations WHERE username = '$username'";
    $result = $db_conn->query($query);
    $now = time();
	
    if ($result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();   
        $expiresAt = $row['expiresAt']; 

        if($expiresAt >= $now)
        {
          echo "User has prior session, clearing then adding!\n";
          $query = "DELETE FROM validations WHERE username = '$username'";
          $result = $db_conn->query($query);

          $key = bin2hex(random_bytes(10));
          $now = time();
          $expTime = $now + $timeToAdd;
          $query = "INSERT INTO validations (username, sessionKey, createdAt, expiresAt)
          VALUES ('$username', '$key', $now, $expTime);";
          $result = $db_conn->query($query);

          return array(
            "status" => "success",
            "key" => $key,
            "message" => "User can stay logged in!"
          );
        }
        else
        {
          echo "User has an expired Key! Boot 'em!\n";
          $query = "DELETE FROM validations WHERE username = '$username'";
          $result = $db_conn->query($query);

          return array(
            "status" => "boot",
            "message" => "User needs to be logged out!"
          );
        }
    }
  }
  return array(
      "status" => "success",
      "key" => $key,
      "message" => ""
  );
}

function requestProcessor($request)
{
  global $db_conn;     
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
    case "watchlist":
      $user = $request['username'];
      $query = "SELECT movie_id, movie_name FROM watchlist WHERE username='$user'";
      $result = $db_conn->query($query);
      $list = [];
      while($row = $result->fetch_assoc()) { $list[] = $row; }
      return $list; // Added the return to fix the hang - ME
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("db_server.ini");
$server->process_requests('requestProcessor');
?>
