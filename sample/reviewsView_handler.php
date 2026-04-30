<?php
require_once('../rabbitMQLib.inc');
include "navbar.php";
$client = new rabbitMQClient("../web_client.ini","db_listen_queue","db_listen");
$request = array();
$request['type'] = "reviewAll";
$response = $client->send_request($request);
?>

<?php include "header.php"; ?>
   <body class="home-body">
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
                  <button style="width: 200px;" onclick="reviewReview(<?php echo $review['id']?>, true)">like</button>
                  <p><?php echo $review['likes'];?></p>
                  <button style="width: 200px;" onclick="reviewReview(<?php echo $review['id']?>, false)">dislike</button>
                  <p><?php echo $review['dislikes'];?></p>
	       </div>
               <?php endforeach; ?>
            <?php else: ?>
               <p style="color: white; text-align center;">No reviews yet for this movie.</p>
            <?php endif; ?>
         </div>
      </main>
   </body>
</html>

<script>
function reviewReview(review_id, stat)
{
   var request = new XMLHttpRequest();
   request.open("POST","review_reviews_handler.php", true);
   request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
   request.onreadystatechange = function () {
       if (this.readyState == 4 && this.status == 200) {
              //addReviews(JSON.parse(this.responseText));   
            console.log(this.responseText);
       }
   }
   let username = sessionStorage.getItem("username");
   let stat_str = (stat) ? 1 : 0;
   request.send(`username=${username}&review_id=${review_id}&status=${stat_str}`);
}
</script>
