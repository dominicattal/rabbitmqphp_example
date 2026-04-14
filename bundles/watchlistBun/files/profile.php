<?php include "header.php"; ?>
<body class="home-body">
   <?php include "navbar.php"; ?>
   <main class="content-wrapper">
      <div class="profile-header" style="text-align: center; margin-bottom: 40px;">
	 <h1 class="section-title">USER PROFILE</h1>
	 <div class="movie-card" style="display: inline-block; padding: 20px; min-width: 300px;">
            <h2 id="profile-name" style="color: var(--accent);"></h2>
            <p id="profile-email" style="margin-top: 10px; font-weight: bold;"></p>
         </div> 
      </div>

      <h2 class="section-title">YOUR REVIEWS</h2>
      <div class="movie-grid" id="reviews-grid"></div>
   </main>
</body>

<script>
const username = sessionStorage.getItem("username");
const email = sessionStorage.getItem("email");

if (username) {
   document.getElementById("profile-name").textContent = `@${username}`;
   document.getElementById("profile-email").textContent = email;
}
else {
   window.location.href="login.html";
}

function addReviews(reviews) {
   let grid = document.getElementById("reviews-grid");
   grid.innerHTML = "";
   if (!Array.isArray(reviews) || reviews.length === 0) {
      grid.innerHTML = "<p style='color: white; text-align: center; padding: 20px;'>No reviews yet.</p>";
      return; 
   }
   reviews.forEach(item => {
      let card = document.createElement("div");
      card.className = "movie-card";
      card.style.padding = "15px";
      card.innerHTML = `
         <img class="movie-poster" src="${item.movie.poster_img_url}" style="height: 250px;">
         <div class="movie-details">
	    <h3 class="movie-title">${item.movie.title}</h3>
            <p style="color: var(--accent); font-weight: bold;">Score: ${item.score}/10</p>
	    <p style="font-size: 0.9em; margin-top: 5px;">"${item.review}"</p>
	 </div>`;
      grid.appendChild(card);
   });
}

function getReviews() {
   var request = new XMLHttpRequest();
   request.open("POST","get_reviews_handler.php", true);
   request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
   request.onreadystatechange = function () {
       if (this.readyState == 4 && this.status == 200) {
              addReviews(JSON.parse(this.responseText));   
       }
   }
   request.send(`username=${username}`);
}
getReviews();
</script>
