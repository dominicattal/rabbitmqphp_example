<?php
$movieId = $_GET['id'] ?? null;
if (!$movieId)
  die("Movie ID missing.");

require_once('../rabbitMQLib.inc');
include('../log.inc');
$client = new rabbitMQClient("web_client.ini", "db_listen_queue", "db_listen");
$request = array();
$request['type'] = "movie";
$request['id'] = $movieId;
$movie = $client->send_request($request);
if ($movie == false){
	maddLog("movie details request failed in web");
	return array("status"=>"failed");
}
$title = $movie['title'];
$overview = $movie['overview'];
$poster = "https://image.tmdb.org/t/p/w500" . $movie['poster_img_url'];
$release_date = $movie['release_date'] ?? 'TBD';

$reviews_link = "reviewsView_handler.php?id=$movieId";
?>

<script>
if(!sessionStorage.getItem("username"))
{
  //At some point this might need to be changed to check for session info aswell - ME
  //alert("User not logged in!");
  window.location.href = "login.html";
}
</script>

<?php include "header.php"; ?>
<body class="home-body">
  <?php include "navbar.php"; ?>

  <main class="container content-wrapper" style="color: white;">
    <div class="row">
      <div class="col-md-4">
        <div class="thumbnail" style="border: 2px solid black; background: transparent;">
          <img src="<?php echo $poster; ?>" class="img-responsive" alt="<?php echo $title; ?>">
        </div>
      </div>

      <div class="col-md-8">
        <div class="text-content">
          <h1 style="color: white; margin-top: 0;"><?php echo $title; ?></h1>
          <p class="synopsis" style="font-size: 1.2em; line-height: 1.6;"><?php echo $overview; ?></p>
          <p style="color: #ccc;">Release Date: <?php echo $release_date; ?></p>

          <button type="button" 
            class="btn btn-primary btn-lg" 
            onclick="addToWatchlist('<?php echo $movieId; ?>', '<?php echo addslashes($title); ?>', '<?php echo $release_date; ?>')">
            ADD TO WATCHLIST
          </button>
          <p id="watchlist-msg" style="margin-top: 10px; font-weight: bold;"></p>
          
          <script>
          function addToWatchlist(id, name, date) {
            const msg = document.getElementById('watchlist-msg');
            msg.textContent = "Adding...";
            let username = sessionStorage.getItem("username");

            fetch('watchlist_add.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `username=${username}&movie_id=${id}&movie_name=${encodeURIComponent(name)}&release_date=${date}`
            })
            .then(response => response.json())
            .then(data => {
              if (data.status === 'success') {
                msg.style.color = "#FF5E5B"; // Cinema Red
                msg.textContent = "Added to your watchlist!";
              } else {
                msg.textContent = data.message || "Already in watchlist!";
              }
            })
          }
          </script>
        </div>
      </div>
    </div>

<!--The stuff to make a review possible -ME
Post request to reviews_handler sending currentpage (defunct), username, movieID, and user's review-->
<hr style="border-top: 1px solid #444;">
<div class="row">
  <div class="col-md-6">
    <form action="reviews_handler.php" method="post" id="review_handler">
      <div class="form-group">
        <input type="hidden" name="currentPage" id="currentPage" value="">
        <label for="username" style="color: white;">Username</label>
        <input type="text" class="form-control" name="username" id="username2" value="TEST VALUE" readonly />
      </div>
          
      <div>
        <input type="hidden" name="movieID" id="movieID" required />
      </div>

      <div class="form-group">
        <label for="message" style="color: white;">Write your review here</label>
        <textarea class="form-control" name="message" id="message" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label for="rating" style="color: white;">Rating out of 10</label>
        <input type="number" class="form-control" name="rating" id="rating" min="0" max="10" step="1" required />
      </div>

      <div class="form-group">
        <label for="updateOrInsert" style="color: white;">Update or Insert?</label>
        <input type="text" class="form-control" name="UOI" id="UOI" class="form-control" placeholder="Type Update or Insert" required />
      </div>

      <button type="submit" class="btn btn-success btn-block" style="font-weight: bold;">Submit</button>
    </form>
    <p id="response"></p>
  </div>

  <div class="col-md-6 text-center">
    <form action="<?php echo $reviews_link;?>" method="post">
      <input type="hidden" name="username" id="username3" value="TEST VALUE" readonly />
      <input type="hidden" name="movieID" id="movieID2" required />
      <div style="margin-top: 25px;">
        <a href"<?php echo $reviews_link;?>"= class="btn btn-info btn-block" style="font-weight: bold;">See all reviews here!</a>
      </div>
    </form>
    <p id="reviewListOne"></p>
  </div>
</div>
</main>

<script>
document.getElementById("username2").value = sessionStorage.getItem("username");
document.getElementById("username3").value = sessionStorage.getItem("username");
document.getElementById("movieID").value = <?php echo $movieId; ?>;
document.getElementById("movieID2").value = <?php echo $movieId; ?>;
</script>
</body>
</html>

