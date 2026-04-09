<?php
require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "db_web_queue", "db_web");
$request = array();
$request['type'] = "popular";
$response = $client->send_request($request);
$movies = $response;
?>

<?php include "header.php"; ?>
<body class="home-body">
    <?php include "navbar.php"; ?>

    <main class="content-wrapper">
    <h2 class="section-title">POPULAR NOW</h2> 
    <div class="movie-grid">
    <?php foreach ($movies as $movie): 
        $title = $movie['title'];
        $movieId = $movie['id']; 
        $poster = $movie['poster_img_url'];
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
