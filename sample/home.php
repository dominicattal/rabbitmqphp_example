<script>
//This if statement checks if a user is logged in
//If not, dumps them at the log in screen -Matt
if(!sessionStorage.getItem("username"))
{
  //At some point this might need to be changed to check for session info aswell
  window.location.href = "login.html";
}
</script>

<?php
require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "db_web_queue", "db_web");
$request = array();
$request['type'] = "popular";
$request['count'] = 10;
$response = $client->send_request($request);
$movies = $response;
// REMOVED upcoming movies request here to restore stability - ME
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
      <div class="logo-container">
          <a href="home.php" class="logo">MADD FOR MOVIES</a>
      </div>

      <div class="nav-links">
	 <a href="home.php" class="nav-btn">HOME</a>
         <a href="higher-lower.php" class="nav-btn">HIGHER/LOWER</a>
         <div class="profile-dropdown">
             <button class="nav-btn">PROFILE ▼</button>
             <div class="dropdown-content">
                 <a href="profile.php">MY ACCOUNT</a>
                 <a href="watchlist.php">WATCHLIST</a>
                 <hr class="dropdown-divider">
                 <a href="login.html" class="logout-link" onclick="sessionStorage.clear()">LOGOUT</a>
             </div>
         </div>
      </div>
   </nav>

    <main class="content-wrapper">
    <h2 class="section-title">POPULAR NOW</h2> 
    <div class="movie-grid">
    <?php foreach ($movies as $movie): 
        $title = htmlspecialchars($movie['title']);
        $movieId = $movie['id']; 
        $poster = "https://image.tmdb.org/t/p/w500" . $movie['poster_img_url'];
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
