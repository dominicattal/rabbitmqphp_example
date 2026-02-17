<?php
if (!isset($_POST)) {
  trigger_error("Missing post data", E_USER_WARNING);
  goto fail;
}
$username = $_POST["username"];
if (!isset($username)) {
  trigger_error("Missing username", E_USER_WARNING);
  goto fail;
}
$password = htmlspecialchars($_POST["password"]);
if (!isset($password)) {
  trigger_error("Missing password", E_USER_WARNING);
  goto fail;
}

header("Location: home.php");
die();

fail:
header("Location: login.php");
die();
?>
