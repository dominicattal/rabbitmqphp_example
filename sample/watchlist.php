<?php include "header.php"; ?>
<body class="home-body">
    <?php include "navbar.php"; ?>
   <main class="container content-wrapper">
      <h1 class="section-title text-center">YOUR WATCHLIST</h1>
      <div id="movie-grid" class="row">
      </div>
   </main>
</body>

<script>
function addMovies(movies)
{
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

    for (movie of movies) {
        let col = document.createElement("div");
        col.className = "col-md-3 col-sm-6"; 

        let movie_link = document.createElement("a");
        movie_link.setAttribute("href", `details.php?id=${movie.id}`);
        movie_link.classList.add("movie-link");
        
        let movie_card = document.createElement("div");
        movie_card.className = "thumbnail";
        movie_card.style.border = "2px solid black";
        movie_card.style.marginBottom = "20px";

        let movie_poster = document.createElement("img");
        movie_poster.setAttribute("src", movie.poster_img_url);
        movie_poster.setAttribute("alt", movie.title);
        movie_poster.className = "img-responsive";
        movie_poster.style.height = "250px";
        movie_poster.style.width = "100%";
        movie_poster.style.objectFit = "cover";

        let movie_details = document.createElement("div");
        movie_details.className = "caption text-center";

        let movie_title = document.createElement("h3");
        movie_title.style.fontSize = "1.1em";
        movie_title.style.fontWeight = "bold";
        movie_title.textContent = movie.title;

        movie_details.appendChild(movie_title);

        if (movie.release_state !== "" ) {
            let movie_date = document.createElement("p");
            movie_date.className = "text-muted";
            movie_date.textContent = movie.release_state;
            movie_details.appendChild(movie_date);
        }

        movie_card.appendChild(movie_poster);
        movie_card.appendChild(movie_details);
        movie_link.appendChild(movie_card);
        col.appendChild(movie_link);
        movie_grid.appendChild(col);
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

