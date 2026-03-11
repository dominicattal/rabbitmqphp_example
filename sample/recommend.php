<?php include "header.php"; ?>
<body class="home-body">
    <?php include "navbar.php"; ?>
   <main class="content-wrapper">
      <h1 class="section-title">RECOMMENDED</h1>

      <div class="movie-grid">
      </div>

   </main>
</body>
<script>
function addMovies(movies)
{
    let found = movies.found;
    if (found) {
        let section_title = document.getElementsByClassName("section-title")[0];
        section_title.textContent = `Since you liked '${movies.movie_title}...'`;
    }
    movies = movies.results;
    let movie_grid = document.getElementsByClassName("movie-grid")[0];
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
function getRecommended() 
{
	var request = new XMLHttpRequest();
    let username = sessionStorage.getItem("username");
	request.open("POST","recommend_handler.php",true);
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


getRecommended();
</script>
</html>
