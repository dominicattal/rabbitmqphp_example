<?php
$movieId = $_GET['id'] ?? null;
if (!$movieId)
    die("Movie ID missing.");

require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "db_web_queue", "db_web");
$request = array();
$request['type'] = "movie";
$request['id'] = $movieId;
$movie = $client->send_request($request);

$title = htmlspecialchars($movie['title']);
$overview = htmlspecialchars($movie['overview']);
$poster = "https://image.tmdb.org/t/p/w500" . $movie['poster_img_url'];
?>

<script>

if(!sessionStorage.getItem("username"))
{
  //At some point this might need to be changed to check for session info aswell - ME
  //alert("User not logged in!");
  window.location.href = "login.html";
}



</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - Details</title>
    <link rel="stylesheet" href="madd.css">
</head>
<body class="home-body">
    <nav class="navbar">
        <div class="logo">MADD FOR MOVIES</div>
        <a href="home.php" class="logout-link">BACK TO BROWSE</a>
    </nav>


    <main class="content-wrapper">
        <div class="details-container">
            <div class="movie-info-card">
                <img src="<?php echo $poster; ?>" class="details-poster">
	        <div class="text-content">
                    <h1><?php echo $title; ?></h1>
                    <p class="synopsis"><?php echo $overview; ?></p>

                    <button type="button" 
                        class="nav-btn" 
                        onclick="addToWatchlist('<?php echo $movieId; ?>', '<?php echo addslashes($title); ?>')">
                        + ADD TO WATCHLIST
                    </button>
                    <p id="watchlist-msg" style="margin-top: 10px; font-weight: bold;"></p>
                   
                   <script>
                   function addToWatchlist(id, name) {
                      const msg = document.getElementById('watchlist-msg');
                      msg.textContent = "Adding...";

                      fetch('watchlist_add.php', {
                         method: 'POST',
                         headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                         body: `movie_id=${id}&movie_name=${encodeURIComponent(name)}`
                      })
                      .then(response => response.json())
                      .then(data => {
                         if (data.status === 'success') {
                            msg.style.color = "#FF5E5B"; // Cinema Red
                            msg.textContent = "Added to your watchlist!";
                         } else {
                            msg.textContent = data.message || "Already in watchlist!";
                         }
                      })
                   }
                   </script>
                </div>
            </div>

	
            <!--<div class="review-section">
                <h2>USER REVIEWS</h2>
                <div class="review-box">
                    <textarea placeholder="Write your review here..."></textarea>
                    <button class="view-btn">SUBMIT REVIEW</button>
                </div>
                <div id="loaded-reviews">
                    <p>No reviews yet. Be the first!</p>
                </div>
            </div>-->

<!--The stuff to make a review possible -ME -->
<form action="reviews_handler.php" method="post" id="review_handler">
<div>
  <input type="hidden" name="currentPage" id="currentPage" value="">
    <label for="username">Username</label>
    <input type="text" name="username" id="username2" value ="TEST VALUE"  readonly />

  </div>
  <div>
    <label for="movieID"></label>
    <input type="hidden" name="movieID" id="movieID" required />
  </div>
  <div>
    <label for="message">Write your review here</label>
    <input type="text" name="message" id="message" required />
  </div>
<div>
  <label for="rating">Rating out of 10</label>
  <input type="number" name="rating" id="rating" min="0" max="10" step="1" required />
</div>
<div>
    <label for="updateOrInsert">Update or Insert?</label>
    <input type="text" name="UOI" id="UOI" required />
  </div>
  <div>
    <input type="submit" value="Submit" />
  </div>
</form>
<p id="response"></p>
</div>

<form action="reviewsView_handler.php" method="post">
<div>
    
    <input type="hidden" name="username" id="username3" value ="TEST VALUE"  readonly />
  </div>
    <div>
    <input type="hidden" name="movieID" id="movieID2" required />
  </div>
  <div>
    <input type="submit" value="See all reviews here!" />
  </div>
</form>
<p id="reviewListOne"></p>
</div>

<!-- Rather than just showing all reviews, ya gotta press a button for it! -ME
Button being replace with a form so I can send data - ME
<form action="reviewsView_handler.php" method="get">
    <button type="submit">Show Reviews</button>
</form>-->
	

    </main>
</body>
</html>

<script>
document.getElementById("username2").value = sessionStorage.getItem("username"); //DO NOT REMOVE THIS OR STUFF BREAKS! -ME
document.getElementById("username3").value = sessionStorage.getItem("username");
document.getElementById("movieID").value = <?php echo $movieId; ?> //Same warning to this, this code pre-fills the username + movieId in the table above to be the correct user + movie
document.getElementById("movieID2").value = <?php echo $movieId; ?>
</script>
