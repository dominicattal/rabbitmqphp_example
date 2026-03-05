<?php
session_start(); // Ensures the session is active to pull the username
require_once('../rabbitMQLib.inc');

// Use the session username set in the login handler
$username = $_SESSION['username'] ?? null;

if (!$username) { 
    header("Location: login.html"); 
    exit(); 
}

$client = new rabbitMQClient("web_client.ini", "db_queue", "db");
$request = [
    'type' => 'watchlist', // Matches the case in your db.php
    'username' => $username
];

$watchlist = $client->send_request($request);

// Safety check to prevent foreach errors
if (!is_array($watchlist)) {
    $watchlist = [];
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
            <?php if (!empty($watchlist)): ?>
                <?php foreach ($watchlist as $movie): ?>
                    <a href="details.php?id=<?php echo $movie['movie_id']; ?>" class="movie-link">
                        <div class="movie-card">
                            <div class="movie-details">
                                <h3 class="movie-title"><?php echo htmlspecialchars($movie['movie_name']); ?></h3>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: white; text-align: center; padding: 20px;">Your watchlist is currently empty.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
