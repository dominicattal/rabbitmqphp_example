<?php include "header.php"; ?>
<body class="home-body">
  <?php include "navbar.php"; ?>
  
  <main class="container content-wrapper" style="color: white;">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3 text-center">
        <h1 style="font-weight: 900; letter-spacing: 2px;">USER PROFILE</h1>
        <div style="border: 2px solid white; padding: 10px; margin-bottom: 40px; background: rgba(255,255,255,0.1);">
          <h2 id="profile-name" style="margin: 0; color: #fff;"></h2>
          <p id="profile-email" style="margin: 5px 0 0 0; opacity: 0.8;"></p>
        </div>
      </div>
    </div>

    <h3 style="margin-bottom: 20px;">ACHIEVEMENTS</h3>
    <div id="achievement-grid" class="row" style="margin-bottom: 50px;">
      </div>

    <h3 style="margin-bottom: 20px;">YOUR REVIEWS</h3>
    <div id="reviews-grid" class="row">
      </div>
  </main>
</body>

<script>
const username = sessionStorage.getItem("username");
const email = sessionStorage.getItem("email");

if (username) {
  <?php 
  if (isset($_GET['username']) && isset($_GET['email'])) {
    echo "document.getElementById('profile-name').textContent = '" . htmlspecialchars($_GET['username']) . "';\n";
    echo "document.getElementById('profile-email').textContent = '" . htmlspecialchars($_GET['email']) . "';\n";
  } else {
    echo "document.getElementById('profile-name').textContent = `@\${username}`;\n";
    echo "document.getElementById('profile-email').textContent = email;\n";
  }
  ?>
} else {
  window.location.href = "login.html";
}

function addAchievements(achievements) {
  let grid = document.getElementById("achievement-grid");
  grid.innerHTML = "";
  
  for (const [name, vals] of Object.entries(achievements)) {
    let col = document.createElement("div");
    col.className = "col-xs-12"; 
    
    let isUnlocked = vals.unlocked;
    let bgColor = isUnlocked ? "#5cb85c" : "#222";
    let textColor = isUnlocked ? "#fff" : "#777";
    let statusLabel = isUnlocked ? "[ UNLOCKED ]" : "[ LOCKED ]";

    col.innerHTML = `
      <div style="background-color: ${bgColor}; color: ${textColor}; padding: 12px; border: 1px solid #444; margin-bottom: 5px; font-family: monospace; font-size: 1.1em;">
        <span style="font-weight: bold; margin-right: 15px;">${statusLabel}</span> 
        ${vals.hr_name}
      </div>`;
    grid.appendChild(col);
  }
}

function addReviews(reviews) {
  let grid = document.getElementById("reviews-grid");
  grid.innerHTML = "";
  
  if (!Array.isArray(reviews) || reviews.length === 0) {
    grid.innerHTML = "<div class='col-xs-12'><p>No activity recorded.</p></div>";
    return; 
  }

  reviews.forEach(item => {
    let col = document.createElement("div");
    col.className = "col-md-3 col-sm-6"; 
    col.innerHTML = `
      <div class="thumbnail" style="background: white; border: 2px solid black; height: 430px; margin-bottom: 20px;">
        <img src="${item.movie.poster_img_url}" style="height: 250px; width: 100%; object-fit: cover;">
        <div class="caption text-center">
          <h4 style="color: black; font-weight: bold; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${item.movie.title}</h4>
          <p style="color: #FF5E5B; font-weight: bold;">Score: ${item.score}/10</p>
          <p style="color: #333; font-size: 0.85em; font-style: italic;">"${item.review}"</p>
        </div>
      </div>`;
    grid.appendChild(col);
  });
}

function getReviews(target) {
  var request = new XMLHttpRequest();
  request.open("POST", "get_reviews_handler.php", true);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      addReviews(JSON.parse(this.responseText));   
    }
  }
  request.send("username=" + target);
}

function getAchievements(target) {
  var request = new XMLHttpRequest();
  request.open("POST", "achievement_handler.php", true);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      addAchievements(JSON.parse(this.responseText));
    }   
  }
  request.send("username=" + target);
}

<?php 
if (isset($_GET['username'])) {
  $userParam = htmlspecialchars($_GET['username']);
  echo "getReviews('$userParam');\n";
  echo "getAchievements('$userParam');\n";
} else {
  echo "getReviews(username);\n";
  echo "getAchievements(username);\n";
}
?>
</script>
</html>

