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
?>

<?php include "header.php"; ?>
   <body class="home-body">
      <?php include "navbar.php"; ?>
      <main class="content-wrapper">
         <h1 class="section-title">USER REVIEWS</h1>
         <div class="reviews-list">
            <?php if (is_array($response) && !empty($response)): ?>
               <?php foreach ($response as $review): ?>
	       <div class="movie-card" style="padding: 20px; margin-bottom: 20px; display: block;">
                  <h3 style="color: var(--accent); margin-bottom: 5px;">@<?php echo htmlspecialchars($review['username']); ?></h3>
	          <p style="font-weight: bold; margin-bottom 10px;">Rating: <?php echo htmlspecialchars($review['score']); ?>/10</p>
                  <p style="font-style: italic; border-left: 4px solid var(--accent); padding-left: 15px;">
                     "<?php echo htmlspecialchars($review['review']); ?>"
                  </p>
	       </div>
               <?php endforeach; ?>
            <?php else: ?>
               <p style="color: white; text-align center;">No reviews yet for this movie.</p>
            <?php endif; ?>
         </div>
      </main>
   </body>
</html>
