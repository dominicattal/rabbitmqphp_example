<script>
<?php

$web_response = "";
$location = "home.html";

if (!isset($_POST)) {
  trigger_error("Missing post data", E_USER_WARNING);
  goto fail;
}

$username = $_POST["username"];
if (!isset($username)) {
    $web_response = "Missing username";
    goto fail;
}

$message = $_POST["message"];
if (!isset($message)) {
  trigger_error("Missing message", E_USER_WARNING);
  goto fail;
}

require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient("../web_client.ini","web_client");

$request = array();
$request['type'] = "validate_session";
$request['username'] = $username;
$request['message'] = $message;



$response = $client->send_request($request);


if (!isset($response["status"])) {
    $web_response = "Internal Error";
    goto fail;
}

if($response["status"] == "boot")
{
 	$web_response = $response["message"];
	$location = "login.html";
	header("location: login.html");
	exit();
}

if($response["status"] == "success")
{
 	$web_response = $response["message"];
	$location = "home.html";
	header("location: home.html");
	exit();
}

if ($response["status"] !== "success") {
    $web_response = $response["message"];
    goto fail;
}


$location = "home.html";

fail:
if ($web_response) {
    trigger_error($web_response, E_USER_WARNING);
    echo "sessionStorage.setItem('message', '$web_response');\n";
} else if (isset($response["sessid"])) {
    echo "sessionStorage.setItem('username', '$username');\n";
    echo "sessionStorage.setItem('key', '$response[key]')\n";
} else {
    trigger_error("how'd this happen", E_USER_WARNING);
}
echo "window.location = '$location';\n";


?>
</script>
