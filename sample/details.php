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
			 }
			 else {
                            msg.textContent = data.message || "Already in watchlist!";
                         }
                      })
                   }
                   </script>
                </div>
            </div>

            <div class="review-section">
                <h2>USER REVIEWS</h2>
                <div class="review-box">
                    <textarea placeholder="Write your review here..."></textarea>
                    <button class="view-btn">SUBMIT REVIEW</button>
                </div>
                <div id="loaded-reviews">
                    <p>No reviews yet. Be the first!</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
