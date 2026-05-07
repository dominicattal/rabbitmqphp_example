<?php
require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "db_listen_queue", "db_listen");
$request = array();
$request['type'] = "popular";
$response = $client->send_request($request);
$movies = $response;
?>

<?php include "header.php"; ?>
<body class="home-body">
    <?php include "navbar.php"; ?>

    <main class="container content-wrapper">
        <h2 class="text-center section-title">POPULAR NOW</h2> 
        
        <div class="row">
            <?php foreach ($movies as $movie): 
                $title = $movie['title'];
                $movieId = $movie['id']; 
                $poster = $movie['poster_img_url'];
            ?>
                <div class="col-md-3 col-sm-6">
                    <a href="details.php?id=<?php echo $movieId; ?>" class="movie-link">
                        <div class="thumbnail movie-card">
                            <img src="<?php echo $poster; ?>" alt="<?php echo $title; ?>" class="img-responsive movie-poster">
                            <div class="caption text-center">
                                <h3 class="movie-title"><?php echo $title; ?></h3>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div> </main>
</body>
</html>

