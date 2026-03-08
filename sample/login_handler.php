<script>
<?php
$web_response = "";
$location = "login.html";

if (!isset($_POST["username"]) || !isset($_POST["password"])) {
    $web_response = "Missing post data";
    goto fail;
}

$username = $_POST["username"];
$password = htmlspecialchars($_POST["password"]);

require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient("../web_client.ini", "db_web_queue", "db_web");

$request = array();
$request['type'] = "login";
$request['username'] = $username;
$request['password'] = $password;

$response = $client->send_request($request);

if (!isset($response["status"])) {
    $web_response = "Internal Error: No response from database.";
    goto fail;
}

if ($response["status"] === "success") {
    $location = "home.php";
} else {
    $web_response = $response["message"];
    goto fail;
}

$request = array();
$request['type'] = "get_email";
$request['username'] = $username;
$email = $client->send_request($request);

fail:
if ($web_response) {
    echo "sessionStorage.setItem('message', '$web_response');\n";
    $location = "login.html";
} else {
    echo "sessionStorage.setItem('username', '$username');\n";
    echo "sessionStorage.setItem('email', '$email');\n";
    echo "sessionStorage.setItem('key', '{$response['key']}');\n";
}

echo "window.location = '$location';\n";
?>
</script>
