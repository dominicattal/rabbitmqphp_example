<?php include "header.php"; ?>
<body class="home-body">
  <?php include "navbar.php"; ?>
  <main class="container content-wrapper">
    <h1 class="section-title text-center">RECOMMENDED</h1>
    <div id="movie-grid" class="row">
    </div>
  </main>
</body>
<script>
function addMovies(movies) {
  let found = movies.found;
  if (found) {
    let section_title = document.getElementsByClassName("section-title")[0];
    section_title.textContent = `Since you liked '${movies.movie_title}...'`;
  }
  
  movies = movies.results;
  let movie_grid = document.getElementById("movie-grid");
  movie_grid.innerHTML = "";
  
  if (movies.length == 0) {
    let p_tag = document.createElement("p");
    p_tag.className = "text-center col-xs-12";
    p_tag.setAttribute("style", "color: white; padding: 20px;");
    p_tag.textContent = "No released movies in your watchlist.";
    movie_grid.appendChild(p_tag);
    return;
  }

  for (let movie of movies) {
    let col = document.createElement("div");
    col.className = "col-sm-6 col-md-3";

    let movie_link = document.createElement("a");
    movie_link.setAttribute("href", `details.php?id=${movie.id}`);
    movie_link.classList.add("movie-link");

    let movie_card = document.createElement("div");
    movie_card.className = "thumbnail";
    movie_card.style.border = "2px solid black";
    movie_card.style.marginBottom = "20px";
    movie_card.style.height = "420px";

    let movie_poster = document.createElement("img");
    movie_poster.setAttribute("src", movie.poster_img_url);
    movie_poster.setAttribute("alt", movie.title);
    movie_poster.className = "img-responsive";
    movie_poster.style.height = "300px";
    movie_poster.style.width = "100%";
    movie_poster.style.objectFit = "cover";

    let movie_details = document.createElement("div");
    movie_details.className = "caption text-center";

    let movie_title = document.createElement("h4");
    movie_title.style.fontWeight = "bold";
    movie_title.style.whiteSpace = "nowrap";
    movie_title.style.overflow = "hidden";
    movie_title.style.textOverflow = "ellipsis";
    movie_title.textContent = movie.title;

    movie_details.appendChild(movie_title);
    movie_card.appendChild(movie_poster);
    movie_card.appendChild(movie_details);
    movie_link.appendChild(movie_card);
    col.appendChild(movie_link);
    movie_grid.appendChild(col);
  }
}

function getRecommended() {
  var request = new XMLHttpRequest();
  let username = sessionStorage.getItem("username");
  request.open("POST", "recommend_handler.php", true);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function() {
    if ((this.readyState == 4) && (this.status == 200)) {
      addMovies(JSON.parse(this.responseText));
    }
  }
  request.send(`username=${username}`);
}

getRecommended();
</script>
</html>

