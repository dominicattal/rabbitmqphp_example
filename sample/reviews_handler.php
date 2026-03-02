<script>
<?php

$web_response = "";
$location = "reviews.html";

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

if($response["status"] == "boot")
{
 	$web_response = $response["message"];
	$location = "login.html";
	header("location: login.html");
	exit();
}

//This is where we make the call to the other terminal to get the data, not worked out on atm
//For now, just making a connnection to the local DB and adding the user's review
//Need to do 2 things, 1 check if user has made a review on this movie before, if so output it for them to edit, else let them make a new one

$request = array();
$request['type'] = "review_movie";
$request['username'] = $username;
$request['message'] = $message;
$request['movieID'] = $movieID;

$response = $client->send_request($request);




fail:
if ($web_response) {
    trigger_error($web_response, E_USER_WARNING);
    echo "sessionStorage.setItem('message', '$web_response');\n";
} else if (isset($response["sessid"])) {
    echo "sessionStorage.setItem('username', '$username');\n";
    //echo "sessionStorage.setItem('key', '$response[key]')\n";
} else {
    trigger_error("how'd this happen", E_USER_WARNING);
}
echo "window.location = '$location';\n";

?>
</script>
