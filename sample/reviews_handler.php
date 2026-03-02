<script>
<?php

$web_response = "";

if (!isset($_POST)) {
  trigger_error("Missing post data", E_USER_WARNING);
  goto fail;
}

$location = $_POST["currentPage"];
if (!isset($location)) {
    $web_response = "Missing location";
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

$movieID = $_POST["movieID"];
if (!isset($movieID)) {
  trigger_error("Missing movieID", E_USER_WARNING);
  goto fail;
}

$UOI = $_POST["UOI"];
if (!isset($UOI)) {
  trigger_error("Missing UOI", E_USER_WARNING);
  goto fail;
}

$rating = $_POST["rating"];
if (!isset($rating)) {
  trigger_error("Missing rating", E_USER_WARNING);
  goto fail;
}

require_once('../rabbitMQLib.inc');

$client = new rabbitMQClient("../web_client.ini","web_client");

/*$request = array();
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
}*/

//This is where we make the call to the other terminal to get the data, not worked out on atm
//For now, just making a connnection to the local DB and adding the user's review
//Need to do 2 things, 1 check if user has made a review on this movie before, if so output it for them to edit, else let them make a new one

if($UOI == "U" || $UOI == "UPDATE")
{
	$request = array();
	$request['type'] = "review_movie";
	$request['username'] = $username;
	$request['message'] = $message;
	$request['movieID'] = $movieID;
	$request['rating'] = $rating;

	$response = $client->send_request($request);

	if($response)
	{
		if($response["status"] == "success")
		{	
		 	$web_response = $response["message"];
			
			$location = "home.html"; //This is to prevent an infinite loop of loading hell. Probably 	fixable -ME
			header("Location: " . $location);
			exit();
		}
		

		if ($response["status"] !== "success") 
		{
		    $web_response = $response["message"];
		    goto fail;
		}
	}
	else
	{
	  goto fail;
	}
}
else if($UOI == "I" || $UOI == "INSERT")
{
	$request = array();
	$request['type'] = "createReview";
	$request['username'] = $username;
	$request['message'] = $message;
	$request['movieID'] = $movieID;
	$requst['rating'] = $rating;

}
else
{
trigger_error("User never declared U or I!!!!!!!!!!", E_USER_WARNING);
	goto fail;
}
 





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
