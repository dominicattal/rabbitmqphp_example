<?php
session_start();
require_once('../rabbitMQLib.inc');

// Use the session username set in the handler
$username = $_SESSION['username'] ?? null;

if (!$username) { 
    header("Location: login.html"); 
    exit(); 
}

$client = new rabbitMQClient("web_client.ini", "db_queue", "db");
$request = [
    'type' => 'watchlist', // Ensure this matches the case in your db.php
    'username' => $username
];

$watchlist = $client->send_request($request);

// Ensure $watchlist is an array to prevent foreach errors
if (!is_array($watchlist)) {
    $watchlist = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Watchlist</title>
    <link rel="stylesheet" href="madd.css">
</head>
<body class="home-body">
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
                <p style="color: white; padding: 20px;">Your watchlist is currently empty.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
