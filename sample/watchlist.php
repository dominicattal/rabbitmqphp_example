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

<?php include "header.php"; ?>
<body class="home-body">
    <?php include "navbar.php"; ?>
   <main class="content-wrapper">
      <h1 class="section-title">YOUR WATCHLIST</h1>

      <div class="movie-grid">
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
<script>
function addMovies(movies)
{
    let movie_grid = document.getElementsByClassName("movie-grid")[0];
    console.log(movies);
    if (movies.length == 0) {
        let p_tag = document.createElement("p");
        p_tag.setAttribute("style", "color: white; text-align: center; padding: 20px;");
        p_tag.textContent = "No released movies in your watchlist.";
        movie_grid.appendChild(p_tag);
        return;
    }
    for (movie of movies) {
        let movie_link = document.createElement("a");
        movie_link.setAttribute("href", `details.php?id=${movie.id}`);
        movie_link.classList.add("movie-link");
        movie_grid.appendChild(movie_link);
        let movie_card = document.createElement("div");
        movie_card.classList.add("movie-card");
        movie_link.appendChild(movie_card);
        let movie_poster = document.createElement("img");
        movie_poster.setAttribute("src", movie.poster_img_url);
        movie_poster.setAttribute("alt", movie.title);
        movie_poster.classList.add("movie-poster");
        movie_card.appendChild(movie_poster);
        let movie_details = document.createElement("div");
        movie_details.classList.add("movie-details");
        movie_card.appendChild(movie_details);
        let movie_title = document.createElement("h3");
        movie_title.classList.add("movie-title");
        movie_title.textContent = movie.title;
        movie_details.appendChild(movie_title);
    }
}
function getWatchlist() 
{
	var request = new XMLHttpRequest();
    let username = sessionStorage.getItem("username");
	request.open("POST","watchlist_handler.php",true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	request.onreadystatechange = function ()
    {
		if ((this.readyState == 4)&&(this.status == 200))
		{
            addMovies(JSON.parse(this.responseText));
		}		
	}
	request.send(`username=${username}`);
}


getWatchlist();
</script>
</html>
