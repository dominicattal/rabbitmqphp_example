<?php
require_once('../rabbitMQLib.inc');

if (!isset($_POST)) {
  trigger_error("Missing post data", E_USER_WARNING);
  goto fail;
}

$username = $_POST["username"];
if (!isset($username)) {
    trigger_error("Missing username", E_USER_WARNING);
    goto fail;
}

$movieID = $_POST["movieID"];
if (!isset($movieID)) {
  trigger_error("Missing movieID", E_USER_WARNING);
  goto fail;
}


$client = new rabbitMQClient("../web_client.ini","db_web_queue","db_web");
$request = array();
$request['type'] = "getAllReviewsOne";
$request['movieID'] = $movieID;
$request['username'] = $username;
$response = $client->send_request($request);

echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr>
            <th>Username</th>
            <th>Media ID</th>
            <th>Score</th>
            <th>Review</th>
          </tr>";

    foreach ($response as $review) {
        echo "<tr>
                <td>{$review['username']}</td>
                <td>{$review['movie_id']}</td>
                <td>{$review['score']}</td>
                <td>{$review['review']}</td>
              </tr>";
    }

/*foreach ($response as $review) 
{
	
    echo "<p>{$review['username']} rated the media {$review['movie_id']}: {$review['score']} {$review['review']}<p>";
}*/

echo "</table>";
exit();

fail:
echo "Something went wrong!";

?>
