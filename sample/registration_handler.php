<script>
<?php

$web_response = "";
$location = "registration.html";

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

require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient("../web_client.ini","web_client");

$request = array();
$request['type'] = "register";
$request['username'] = $username;
$request['password'] = $password;
$response = $client->send_request($request);
if (!isset($response["status"])) {
    $web_response = "Internal Error";
    goto fail;
}
if ($response["status"] !== "success") {
    $web_response = $response["message"];
    goto fail;
}

$response["sessid"] = "test";
$location = "home.html";

fail:
if ($web_response) {
    trigger_error($web_response, E_USER_WARNING);
    echo "sessionStorage.setItem('message', '$web_response');\n";
} else if (isset($response["sessid"])) {
    echo "sessionStorage.setItem('username', '$username');\n";
    echo "sessionStorage.setItem('sessid', '$response[sess_id]');\n";
} else {
    trigger_error("how'd this happen", E_USER_WARNING);
}
echo "window.location = '$location';\n";
?>
</script>
