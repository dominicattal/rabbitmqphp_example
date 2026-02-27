<script>
<?php

$web_response = "";
$location = "home.html";

if (!isset($_POST)) {
	echo"Error with Post!\n";
  trigger_error("Missing post data", E_USER_WARNING);
  goto fail;
}

$username = $_POST["username"];
if (!isset($username)) {
	echo"No Username!\n";
    $web_response = "Missing username";
    goto fail;
}

$message = $_POST["message"];
if (!isset($message)) {
  trigger_error("Missing message", E_USER_WARNING);
	echo "No post message!\n";
  goto fail;
}

echo "Inside mattTesting!";
require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient("../web_client.ini","web_client");

$request = array();
$request['type'] = "validate_session";
$request['username'] = $username;
$request['message'] = $message;

echo"Have not sent request yet!\n";

$response = $client->send_request($request);
echo"Have sent request!\n";

if (!isset($response["status"])) {
    $web_response = "Internal Error";
    echo "Internal Error!\n";
    goto fail;
}

if($response["status"] == "boot")
{
 	$web_response = $response["message"];
	$location = "login.html";
	echo "window.location = '$location';\n";
	goto redirect;
}

if ($response["status"] !== "success") {
    $web_response = $response["message"];
    echo "No response!\n";
    goto fail;
}


echo "Returning to home!\n";
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

//temp solution? -ME 2/27
redirect:
$location = "login.html";
echo "window.location = '$location';\n";
?>
</script>
