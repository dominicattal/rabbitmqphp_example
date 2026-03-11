<?php
require_once('../rabbitMQLib.inc');
include "navbar.php";
$client = new rabbitMQClient("../web_client.ini","db_web_queue","db_web");
$request = array();
$request['type'] = "reviewAll";
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
