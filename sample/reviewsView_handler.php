<?php
require_once('../rabbitMQLib.inc');
include "navbar.php";
$client = new rabbitMQClient("../web_client.ini","db_web_queue","db_web");
$request = array();
$request['type'] = "reviewAll";
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
