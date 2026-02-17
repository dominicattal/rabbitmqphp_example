<?php

goto fail;
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

// create db connection here to create user
require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient("../testRabbitMQ.ini","testServer");

$request = array();
$request['type'] = "register";
$request['username'] = $_POST["username"];
$request['password'] = $_POST["password"];
$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
fail:
?>
<script>
sessionStorage.setItem("test2", "test2");
window.location = "home.php";
</script>
