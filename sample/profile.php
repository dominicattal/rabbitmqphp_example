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

      <h2 class="section-title">YOUR ACHIEVEMENTS</h2>
      <div class="achievements achievement-grid">
      </div>

      <h2 class="section-title">YOUR REVIEWS</h2>
      <div class="movie-grid" id="reviews-grid"></div>
   </main>
</body>

<script>
const username = sessionStorage.getItem("username");
const email = sessionStorage.getItem("email");

if (username) {
	<?php 
	if (isset($_GET['username']) && isset($_GET['email'])) {
		echo "document.getElementById('profile-name').textContent = '$_GET[username]'\n;";
		echo "document.getElementById('profile-email').textContent = '$_GET[email]'\n;";
	} else {
		echo "document.getElementById('profile-name').textContent = `@\${username}`\n;";
		echo "document.getElementById('profile-email').textContent = email\n;";
	}
	?>
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

function getReviews(explicit_username) {
   var request = new XMLHttpRequest();
   request.open("POST","get_reviews_handler.php", true);
   request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
   request.onreadystatechange = function () {
       if (this.readyState == 4 && this.status == 200) {
              addReviews(JSON.parse(this.responseText));   
       }
   }
   request.send(`username=${explicit_username}`);
}

getReviews();

function addAchievements(achievements)
{
    let achievement_grid = document.getElementsByClassName("achievement-grid")[0];
    console.log(achievement_grid);
    console.log(achievements);
    for (const [name, vals] of Object.entries(achievements)) {
        let achievement_card = document.createElement("div");
        achievement_card.classList.add("movie-card");
        achievement_card.textContent = vals.hr_name;
        if (vals.unlocked)
            achievement_card.setAttribute("style", "display: inline-block; padding: 20px; min-width: 300px; color: green;");
        else
            achievement_card.setAttribute("style", "display: inline-block; padding: 20px; min-width: 300px; color: red;");
        achievement_grid.appendChild(achievement_card);
    }
}
function getAchievements(explicit_username) 
{
	var request = new XMLHttpRequest();
    let username = sessionStorage.getItem("username");
	request.open("POST","achievement_handler.php",true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	request.onreadystatechange = function ()
    {
		if ((this.readyState == 4)&&(this.status == 200))
		{
            addAchievements(JSON.parse(this.responseText));
		}		
	}
	request.send(`username=${explicit_username}`);
}

<?php 
if (isset($_GET['username'])) {
	echo "getReviews('$_GET[username]');\n";
	echo "getAchievements('$_GET[username]');\n";
} else {
	echo "getReviews(username);\n";
	echo "getAchievements(username);\n";
}
?>
</script>
