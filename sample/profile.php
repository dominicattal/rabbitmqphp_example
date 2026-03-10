<?php include "header.php"; ?>
<body class="home-body">
    <?php include "navbar.php"; ?>
    <main class="content-wrapper">
        <h2 class="section-title">PROFILE</h2> 
        <div class="details-container">
            <h5 id="profile-name"></h5>
            <h5 id="profile-email"></h5>
        </div>
        <h2 class="section-title">REVIEWS</h2> 
        <div class="movie-grid">
            <div class="review-container">
                <img class="review-movie-img" src="https://image.tmdb.org/t/p/w500https://image.tmdb.org/t/p/w500/oJ7g2CifqpStmoYQyaLQgEU32qO.jpg" width="120px" height="300px"></img>
                <h3 class="review-movie-title">Title of Movie Here</h3>
                <p class="review-score">Score of movie here</p>
                <p class="review">Review of movie here</p>
            </div>
        </div>
    </main>
</body>

<script>
var username = sessionStorage.getItem("username");
var email = sessionStorage.getItem("email");
if (username) {
    let profile_name = document.getElementById("profile-name");
    profile_name.textContent = `Username: ${username}`;
    let profile_email = document.getElementById("profile-email");
    profile_email.textContent = `Email: ${email}`;
} else {
    window.location.href="login.html";
}

function addReviews(movies)
{
    let movie_grid = document.getElementsByClassName("movie-grid")[0];
    console.log(movies);
    for (movie of movies) {
        let review_container = document.createElement("div");
        review_container.classList.add("review-container");
        movie_grid.appendChild(review_container);
        let review_movie_img = document.createElement("img");
        review_movie_img.setAttribute("src", movie.movie.poster_img_url);
        review_movie_img.setAttribute("width", "120px");
        review_movie_img.setAttribute("height", "300px");
        review_movie_img.classList.add("review-movie-img");
        review_container.appendChild(review_movie_img);
        let review_movie_title = document.createElement("h3");
        review_movie_title.classList.add("review-movie-title");
        review_movie_title.textContent = movie.movie.title;
        review_container.appendChild(review_movie_title);
        let review_score = document.createElement("p");
        review_score.classList.add("review-score");
        review_score.textContent = movie.score;
        review_container.appendChild(review_score);
        let review = document.createElement("p");
        review.classList.add("review");
        review.textContent = movie.review;
        review_container.appendChild(review);
    }
}

function getReviews() 
{
	var request = new XMLHttpRequest();
    let username = sessionStorage.getItem("username");
	request.open("POST","get_reviews_handler.php",true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	request.onreadystatechange = function ()
    {
		if ((this.readyState == 4)&&(this.status == 200))
		{
            addReviews(JSON.parse(this.responseText));
		}		
	}
	request.send(`username=${username}`);
}

getReviews();
</script>
