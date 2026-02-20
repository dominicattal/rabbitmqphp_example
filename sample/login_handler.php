<script>
<?php

$web_response = "";
$location = "login.html";

if (!isset($_POST)) {
    $web_response = "Missing post data";
    goto fail;
}
$username = $_POST["username"];
if (!isset($username)) {
    $web_response = "Missing username";
    goto fail;
}
$password = htmlspecialchars($_POST["password"]);
if (!isset($password)) {
    $web_response = "Missing password";
    goto fail;
}

require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient("../web_client.ini","web_client");

$request = array();
$request['type'] = "login";
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
