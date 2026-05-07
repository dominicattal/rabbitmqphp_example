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
include('../log.inc');
$search = $_GET['search'] ?? null;
$movies = [];
if($search){
$client = new rabbitMQClient("web_client.ini", "db_listen_queue", "db_listen");
$request = array();
$request['type'] = "search";
$request['query'] = $search;
$response = $client->send_request($request);
if ($response == false){
	maddLog("search request failed in web");
	return array("status"=>"failed");
}
$movies = $response['results'] ?? [];
}
?>

<script>
if(!sessionStorage.getItem("username")) {
  window.location.href = "login.html";
}
</script>

<?php include "header.php"; ?>
<body class="home-body">
  <?php include "navbar.php"; ?>

  <main class="container content-wrapper">
    <h2 class="section-title text-center">
      <?php echo $search ? "RESULTS FOR: " . htmlspecialchars($search) : "SEARCH RESULTS"; ?>
    </h2>

    <div id="movie-grid" class="row">
      <?php if (!empty($movies)): ?>
        <?php foreach ($movies as $movie):
          $title = $movie['title'];
          $movieId = $movie['id'];
          $poster = $movie['poster_img_url'];
        ?>
          <div class="col-md-3 col-sm-6">
            <a href="details.php?id=<?php echo $movieId; ?>" class="movie-link">
              <div class="thumbnail movie-card" style="height: 420px; border: 2px solid black; margin-bottom: 20px;">
                <img src="<?php echo $poster; ?>"
                     alt="<?php echo htmlspecialchars($title); ?>"
                     class="img-responsive"
                     style="height: 300px; width: 100%; object-fit: cover;">
                <div class="caption text-center">
                  <h4 style="font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <?php echo $title; ?>
                  </h4>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-xs-12">
          <div class="alert alert-info text-center" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid white;">
            No movies found for "<?php echo htmlspecialchars($search); ?>".
          </div>
        </div>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>

