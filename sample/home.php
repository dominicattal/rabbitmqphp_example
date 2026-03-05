<?php
require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "data_queue", "data");
$request = array();
$request['type'] = "popular";
$request['count'] = 10;
$response = $client->send_request($request);
$movies = $response["results"];
?>

<script>
//Do not forgot to add this to each webpage to prevent non logged in users from logging in! -ME
if(!sessionStorage.getItem("username"))
{
	window.location.href="login.html";
}
</script>

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
</main>
</body>
</html>
