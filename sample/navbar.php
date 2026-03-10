<nav class="navbar">
   <div class="logo-container">
       <a href="home.php" class="logo">MADD FOR MOVIES</a>
   </div>

   <div class="nav-links">
      <a href="home.php" class="nav-btn">HOME</a>
      <a href="upcoming.php" class="nav-btn">UPCOMING</a>
      <a href="higherlower.php" class="nav-btn">HIGHER/LOWER</a>
      <a href="reviewsView.html" class="nav-btn">REVIEWS</a>
      <div class="profile-dropdown">
          <button class="nav-btn">PROFILE ▼</button>
          <div class="dropdown-content">
              <a href="profile.php">MY ACCOUNT</a>
              <a href="watchlist.php">WATCHLIST</a>
              <hr class="dropdown-divider">
              <a href="login.html" class="logout-link" onclick="sessionStorage.clear()">LOGOUT</a>
          </div>
      </div>
   </div>
</nav>
