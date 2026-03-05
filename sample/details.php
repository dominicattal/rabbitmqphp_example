<?php
$movieId = $_GET['id'] ?? null;
if (!$movieId)
    die("Movie ID missing.");

require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "data_queue", "data");
$request = array();
$request['type'] = "movie";
$request['id'] = $movieId;
$movie = $client->send_request($request);

$title = htmlspecialchars($movie['title']);
$overview = htmlspecialchars($movie['overview']);
$poster = "https://image.tmdb.org/t/p/w500" . $movie['poster_path'];
$backdrop = "https://image.tmdb.org/t/p/original" . $movie['backdrop_path'];
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
    
                    <form method="POST" action="watchlist_add.php">
                        <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">
                        <input type="hidden" name="movie_name" value="<?php echo $title; ?>">
                        <button type="submit" class="nav-btn" style="margin-top: 20px;">+ ADD TO WATCHLIST</button>
                    </form>
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
