<script>
//This if statement checks if a user is logged in
//If not, dumps them at the log in screen
  //At some point this might need to be changed to check for session info aswell - ME
if(!sessionStorage.getItem("username"))
{

  window.location.href = "login.html";
}
</script>

<?php
require_once('../rabbitMQLib.inc');
$search = $_GET['search'] ?? null;
$movies = [];
$client = new rabbitMQClient("web_client.ini", "db_web_queue", "db_web");
$request = array();
$request['type'] = "search";
$request['query'] = $search;
$response = $client->send_request($request);
$movies = $response['results'];
?>

<?php include "header.php"; ?>
<body class="home-body">
    <?php include "navbar.php"; ?>

    <main class="content-wrapper">
    <h2 class="section-title">YOUR RESULTS</h2>
    <div class="movie-grid">
       <?php foreach ($movies as $movie): 
          $title = $movie['title'];
          $movieId = $movie['id']; 
          $poster = $movie['poster_img_url'];
       ?>
       
	<a href="details.php?id=<?php echo $movieId; ?>" class="movie-link">
            <div class="movie-card">
                <div class="poster-container">
                    <img src="<?php echo $poster; ?>" alt="<?php echo $title; ?> Poster" class="movie-poster">
		</div>
                <h3><?php echo $title; ?></h3>

		<?php if (!empty($movie['release_state'])): ?>
                   <p class="movie-date"><?php echo $movie['release_state']; ?></p>
                <?php endif; ?>
            </div>
	</a>
	<?php endforeach; ?>
    </div>
    </main>
</body>
</html>
