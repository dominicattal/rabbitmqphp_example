<!DOCTYPE html>
<html>
<head>
<script>
<?php

$location = "registration.php";
$response = "idk how this happened";

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

set_time_limit(5);

// create db connection here to create user
// set a timeout in case it takes too long
// require_once('../path.inc');
// require_once('../get_host_info.inc');
// require_once('../rabbitMQLib.inc');
//  
// $client = new rabbitMQClient("../testRabbitMQ.ini","testServer");
// 
// $request = array();
// $request['type'] = "register";
// $request['username'] = $_POST["username"];
// $request['password'] = $_POST["password"];
// $response = $client->send_request($request);
// trigger_error("A", E_USER_WARNING);
//$response = $client->publish($request);

// print_r($response);

$location = "home.php";

fail:
echo "window.location = '$location';\n";
echo "sessionStorage.setItem('SESSIONID', '$response');\n";
?>
</script>
</head>
<body>
test
</body>
