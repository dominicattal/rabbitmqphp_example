<script>
//This if statement checks if a user is logged in
//If not, dumps them at the log in screen -Matt
if(!sessionStorage.getItem("username"))
{
  //At some point this might need to be changed to check for session info aswell
  //alert("User not logged in!");
  window.location.href = "login.html";
}
</script>

<?php
// Load API Configuration
$settings = parse_ini_file("../.api.ini", true);
$apiKey = $settings['tmdb']['api_key'] ?? '917c0ffbd6f072c36aba70b11674cf39';

// Fetch Data from TMDB
$url = "https://api.themoviedb.org/3/movie/popular?api_key=" . $apiKey . "&language=en-US&page=1";
$response = file_get_contents($url);
$data = json_decode($response, true);
$movies = $data['results'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MADD FOR MOVIES - Home</title>
    <link rel="stylesheet" href="madd.css">
</head>
<body class="home-body">
    <nav class="navbar">
        <div class="logo">MADD FOR MOVIES</div>
        <div class="nav-links">
            <span id="user-display">Welcome, <em id="username-span"></em></span>
            <a href="login.html" class="logout-link" onclick="sessionStorage.clear()">LOGOUT</a>
        </div>
    </nav>

    <main class="content-wrapper">
    <div class="movie-grid">
    <?php foreach ($movies as $movie): 
        $title = htmlspecialchars($movie['title']);
        $movieId = $movie['id']; // Get the unique ID from TMDB
        $poster = "https://image.tmdb.org/t/p/w500" . $movie['poster_path'];
    ?>
        <a href="details.php?id=<?php echo $movieId; ?>" class="movie-link">
            <div class="movie-card">
                <div class="poster-container">
                    <img src="<?php echo $poster; ?>" alt="<?php echo $title; ?>" class="movie-poster">
                </div>
                <div class="movie-details">
                    <h3 class="movie-title"><?php echo $title; ?></h3>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
    </div>

<a href="reviews.html">Test Create a Review Page</a><br>
<a href="reviewsView.html">View all reviews Page</a>

</main>
</body>
</html>
