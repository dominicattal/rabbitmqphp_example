<script>
<?php

$web_response = "";
$location = "registration.html";

if (!isset($_POST)) {
  trigger_error("Missing post data", E_USER_WARNING);
  //LOG DIS ALY
  goto fail;
}
$username = $_POST["username"];
if (!isset($username)) {
  trigger_error("Missing username", E_USER_WARNING);
  //LOG DIS ALY
  goto fail;
}
$email = $_POST["email"];
if (!isset($email)) {
  trigger_error("Missing email", E_USER_WARNING);
  //LOG DIS ALY
  goto fail;
}
$password = htmlspecialchars($_POST["password"]);
if (!isset($password)) {
  trigger_error("Missing password", E_USER_WARNING);
  //LOG DIS ALY
  goto fail;
}

require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient("../web_client.ini","db_listen_queue","db_listen");

$encryptedPassword = password_hash($password,PASSWORD_DEFAULT);

$request = array();
$request['type'] = "register";
$request['username'] = $username;
$request['email'] = $email;
$request['password'] = $encryptedPassword;
$response = $client->send_request($request);
if (!isset($response["status"])) {
    $web_response = "Internal Error";
    //LOG DIS ALY
    goto fail;
}
if ($response["status"] !== "success") {
    $web_response = $response["message"];
    //LOG DIS ALY
    goto fail;
}

$response["sessid"] = "test";
$location = "login.html";

$request = array();
$request['type'] = "get_email";
$request['username'] = $username;
$email = $client->send_request($request);

fail:
if ($web_response) {
    trigger_error($web_response, E_USER_WARNING);
    echo "sessionStorage.setItem('message', '$web_response');\n";
} else if (isset($response["sessid"])) {
    echo "sessionStorage.setItem('username', '$username');\n";
    echo "sessionStorage.setItem('email', '$email');\n";
    echo "sessionStorage.setItem('key', '$response[key]')\n";

} else {
	//LOG DIS ALY
    trigger_error("how'd this happen", E_USER_WARNING);
}
echo "window.location = '$location';\n";
?>
</script>
