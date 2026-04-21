<nav class="navbar navbar-default">
  <div type="container-fluid">
    <div type="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#Navbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="home.php">MADD FOR MOVIES</a>
    </div>

    <div class="collapse navbar-collapse" id="Navbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="home.php">HOME</li>
        <li><a href="upcoming.php">UPCOMING</li>
        <li><a href="higherlower.php">HIGHER/LOWER</li>
        <li><a href="recommend.php">RECOMMENDED</li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
	  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <span class="glyphicon glyphicon-user"></span>PROFILE<span class="caret"></span>
          </a>
	  <ul class="dropdown-menu">
            <li><a href="profile.php">MY ACCOUNT</a></li>
            <li><a href="watchlist.php">WATCHLIST</a></li>
            <li class="divider"></li>
            <li><a href"login.html" onclick="sessionStorage.clear()">LOGOUT</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>  

<!--
OLD CODE
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
