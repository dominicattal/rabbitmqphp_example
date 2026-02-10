<?php
session_start();
if (!isset($_POST)) {
  $_SESSION["registration_response"] = "Internal Error";
  trigger_error("Missing post data", E_USER_WARNING);
  goto fail;
}
$username = $_POST["username"];
if (!isset($username)) {
  $_SESSION["registration_response"] = "Internal Error";
  trigger_error("Missing username", E_USER_WARNING);
  goto fail;
}
$password = htmlspecialchars($_POST["password"]);
if (!isset($password)) {
  $_SESSION["registration_response"] = "Internal Error";
  trigger_error("Missing password", E_USER_WARNING);
  goto fail;
}
// create db connection here to create user

$_SESSION["username"] = $username;

header("Location: home.php");
die();

fail:
header("Location: registartion.php");
die();
?>
