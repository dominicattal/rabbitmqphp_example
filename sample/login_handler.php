<?php
session_start(); 
?>
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

$client = new rabbitMQClient("../web_client.ini", "db_queue", "db");

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
    $_SESSION['username'] = $username;
    $location = "home.php";
} else {
    // The password was wrong or user wasn't found
    $web_response = $response["message"];
    goto fail;
}

fail:
if ($web_response) {
    // Send the error message back to the login screen
    echo "sessionStorage.setItem('message', '$web_response');\n";
    $location = "login.html";
} else {
    // Set local session storage for the frontend
    echo "sessionStorage.setItem('username', '$username');\n";
    if (isset($response["key"])) {
        echo "sessionStorage.setItem('key', '{$response['key']}');\n";
    }
}

echo "window.location = '$location';\n";
?>
</script>
