<nav class="navbar navbar-inverse" style="border-radius: 0; margin-bottom: 20px; border-bottom: 3px solid black;">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#Navbar" aria-expanded="false">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="home.php" style="font-weight: bold; color: white;">MADD FOR MOVIES</a>
    </div>

    <div class="collapse navbar-collapse" id="Navbar">
      
      <form class="navbar-form navbar-left" action="search.php" method="GET">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Type movie name here">
        </div>
      </form>

      <ul class="nav navbar-nav navbar-right">
        <li><a href="home.php">HOME</a></li>
        <li><a href="upcoming.php">UPCOMING</a></li>
        <li><a href="higherlower.php">HIGHER/LOWER</a></li>
        <li><a href="recommend.php">RECOMMENDED</a></li>
        <li><a href="searchProfile.php">USERS</a></li>
        
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            PROFILE <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="profile.php">MY ACCOUNT</a></li>
            <li><a href="watchlist.php">WATCHLIST</a></li>
            <li><a href="login.html" onclick="sessionStorage.clear()">LOGOUT</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!--
<nav class="navbar">
   <div class="logo-container">
       <a href="home.php" class="logo">MADD FOR MOVIES</a>
   </div>

   <div class="searchbar-container">
      <form action="search.php" method="GET" style="display: flex; gap: 0; align-items: center;">
         <input type="text" name="search" placeholder="Type movie name here" class="searchbar-input">
         <button type="submit" class="searchbar-btn"></button>
      </form>
   </div>

   <div class="nav-links">
      <a href="home.php" class="nav-btn">HOME</a>
      <a href="upcoming.php" class="nav-btn">UPCOMING</a>
      <a href="higherlower.php" class="nav-btn">HIGHER/LOWER</a>
      <a href="recommend.php" class="nav-btn">RECOMMENDED</a>
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
-->

