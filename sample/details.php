<?php
// 1. Session check to ensure only logged-in users see details
session_start();

// 2. Capture the Movie ID from the URL
$movieId = $_GET['id'] ?? null;
if (!$movieId) {
    die("Movie ID missing.");
}

// 3. Load API Key
$ini = parse_ini_file("../.api.ini", false);
$key = $ini["API_KEY"];

// 4. Fetch specific movie details from TMDB
$url = "https://api.themoviedb.org/3/movie/" . $movieId . "?api_key=" . $key . "&language=en-US";
$response = file_get_contents($url);
$movie = json_decode($response, true);

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
