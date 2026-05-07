<script>
//This if statement checks if a user is logged in
//If not, dumps them at the log in screen -Matt
if(!sessionStorage.getItem("username"))
{
  //LOG DIS ALY
  //At some point this might need to be changed to check for session info aswell
  window.location.href = "login.html";
}
</script>

<?php
require_once('../rabbitMQLib.inc');
include('../log.inc');
$client = new rabbitMQClient("web_client.ini", "db_listen_queue", "db_listen");
$request = array();
$request['type'] = "upcoming";
$request['count'] = 10;
$response = $client->send_request($request);
if ($response == false){
	maddLog("upcoming movies request failed in web");
	return array("status"=>"failed");
}
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

<?php include "header.php"; ?>
<body class="home-body">
  <?php include "navbar.php"; ?>
  <main class="container content-wrapper">
    <h2 class="section-title text-center">UPCOMING</h2> 
    
    <div class="row">
      <?php if (is_array($movies) && !empty($movies)): ?>
        <?php foreach ($movies as $movie): 
          $title = $movie['title'];
          $movieId = $movie['id']; 
          $poster = "https://image.tmdb.org/t/p/w500" . $movie['poster_img_url'];
        ?>
          <div class="col-sm-6 col-md-3">
            <a href="details.php?id=<?php echo $movieId; ?>" class="movie-link">
              <div class="thumbnail movie-card" style="height: 420px; border: 2px solid black; margin-bottom: 20px;">
                <div class="poster-container">
                  <img src="<?php echo $poster; ?>" 
                       alt="<?php echo htmlspecialchars($title); ?>" 
                       class="img-responsive"
                       style="height: 300px; width: 100%; object-fit: cover;">
                </div>
                <div class="caption text-center">
                  <h4 style="font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <?php echo $title; ?>
                  </h4>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>

