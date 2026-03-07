<?php
require_once('../rabbitMQLib.inc');

$username = "test";
$client = new rabbitMQClient("web_client.ini", "db_web_queue", "db_web");
$request = [
    'type' => 'watchlist',
    'username' => $username
];

$watchlist = $client->send_request($request);

$released = [];
$upcoming = [];
$today = date("Y-m-d");

if (is_array($watchlist)) {
   foreach ($watchlist as $movie) {
      if (!empty($movie['release_date']) && $movie['release_date'] > $today) {
         $upcoming[] = $movie;
      }
      else {
         $released[] = $movie;
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Watchlist - MADD FOR MOVIES</title>
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
      <h1 class="section-title">YOUR WATCHLIST</h1>
      <div class="movie-grid">
         <?php if (!empty($released)): ?>
            <?php foreach ($released as $movie): ?>
               <a href="details.php?id=<?php echo $movie['movie_id']; ?>" class="movie-link">
                  <div class="movie-card">
                     <div class="movie-details">
                        <h3 class="movie-title"><?php echo htmlspecialchars($movie['movie_name']); ?></h3>
                     </div>
		  </div>
	       </a>
            <?php endforeach; ?>
         <?php else: ?>
            <p style="color: white; text-align: center; padding: 20px;">No released movies in your watchlist.</p>
         <?php endif; ?>
      </div>

      <h1 class="section-title" style="margin-top: 40px;">UPCOMING MOVIES</h1>
      <div class="movie-grid">
         <?php if (!empty($upcoming)): ?>
            <?php foreach ($upcoming as $movie): ?>
               <a href="details.php?id=<?php echo $movie['movie_id']; ?>" class="movie-link">
		  <div class="movie-card" style="border-color: #FF5E5B;"> <div class="movie-details">
		     <h3 class="movie-title"><?php echo htmlspecialchars($movie['movie_name']); ?></h3>
		     <p style="color: #FF5E5B; font-size: 0.8em;">Releasing: <?php echo $movie['release_date']; ?></p>
                     </div>
                  </div>
               </a>
	    <?php endforeach; ?>
	 <?php else: ?>
            <p style="color: white; text-align: center; padding: 20px;">No upcoming movies in your watchlist.</p>
         <?php endif; ?>
      </div>
   </main>
</body>
</html>
