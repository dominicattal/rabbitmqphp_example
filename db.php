#!/bin/php
<?php
require_once('rabbitMQLib.inc');

//This is a flag to indicate which DB is currently in use - ME
$current_db = 1;

$config = parse_ini_file('db_mysql.ini');

$db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);

define("API_CACHE_DURATION", 60*60*24);

function doLogin($username)
{
  global $db_conn;
  $query = "SELECT password FROM users WHERE username ='$username'";
  $result = $db_conn->query($query);
  
 if ($result->num_rows == 0) {
    return array(
      "status" => "failed",
      "message" => "User not found"
    );
  }
  $row = $result->fetch_assoc();
  return array(
   "status" => "success",
   "message" => "Check if password is the same as the encrypted one!",
   "password" => $row["password"]
  );

  /*$query = "SELECT username, password FROM users WHERE username='$username'";
  $result = $db_conn->query($query);
  if ($result->num_rows == 0) {
    return array(
      "status" => "failed",
      "message" => "User not found"
    );
  }
  $row = $result->fetch_assoc();
  if ($password !== $row["password"]) {
    return array(
      "status" => "failed",
      "message" => "Invalid password"
    );
  }

  echo "User logging in, validating!\n";

  //First need to check if the user has a valid session already, if yes kill it !!! - ME
  $query = "SELECT username FROM validations WHERE username='$username'";
  $result = $db_conn->query($query);
  
  if ($result->num_rows > 0) 
  {
    echo "User has an expired Key! Killing it!\n";
    $query = "DELETE FROM validations WHERE username = '$username'";
    $result = $db_conn->query($query);
  }

  $arr = doValidate($username);
  if (!isset($arr["status"]) || $arr["status"] != "success") {
      return array(
          "status" => "failed",
          "message" => "validation failed"
      );
  }

  return array(
    "status" => "success",
    "key" => $arr["key"],
    "message" => "Login Successful"
  );*/
}

function escapeString($str)
{
    $prev = array('\\', '\'');
    $new = array('\\\\', '\\\'');
    $new_str = str_replace($prev, $new, $str);
    return $new_str;
}

function unescapeString($str)
{
    $prev = array('\\\\', '\\\'');
    $new = array('\\', '\'');
    $new_str = str_replace($prev, $new, $str);
    return $new_str;
}

function doRegister($username,$email,$password)
{
  global $db_conn;
  $query = "SELECT username FROM users WHERE username='$username'";
  $result = $db_conn->query($query);
  if ($result->num_rows != 0) {
      return array(
          "status" => "failed",
          "message" => "User exists"
      );
  }
  $query = "INSERT INTO users (username, email, password) VALUES ('$username','$email','$password');";
  $result = $db_conn->query($query);
  $arr = doValidate($username);

  return array(
    "status" => "success",
    "key" => $arr["key"],
    "message" => ""
  );
}

function getEmail($username)
{
  global $db_conn;
  $query = "SELECT email FROM users WHERE username='$username'";
  $result = $db_conn->query($query);
  if ($result->num_rows == 0)
      return "404";
  $row = $result->fetch_assoc();
  return $row["email"];
}

function doValidate($username)
{
  //Making the validations update to make a sessionKey -ME
  echo "Trying a validation!\n";
    global $db_conn;
    $query = "SELECT username from validations where username='$username'";
    $result = $db_conn->query($query);
    $key = "";
    $timeToAdd=300;
    
    var_dump($result);
    var_dump($username);
	
   if ($result->num_rows == 0)
   {
    echo "No user sessions, creating one!\n";
    $key = bin2hex(random_bytes(10));
    $now = time();
    $expTime = $now + $timeToAdd;
    $query = "INSERT INTO validations (username, sessionKey, createdAt, expiresAt)
        VALUES ('$username', '$key', $now, $expTime);";
    $result = $db_conn->query($query);
   }
   else
   {
    //Check if the user is expired. If not clear the old time and give them a new one/new key - ME
    $query = "SELECT expiresAt FROM validations WHERE username = '$username'";
    $result = $db_conn->query($query);
    $now = time();
	
    if ($result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();   
        $expiresAt = $row['expiresAt']; 

        if($expiresAt >= $now)
        {
          echo "User has prior session, clearing then adding!\n";
          $query = "DELETE FROM validations WHERE username = '$username'";
          $result = $db_conn->query($query);

          $key = bin2hex(random_bytes(10));
          $now = time();
          $expTime = $now + $timeToAdd;
          $query = "INSERT INTO validations (username, sessionKey, createdAt, expiresAt)
          VALUES ('$username', '$key', $now, $expTime);";
          $result = $db_conn->query($query);

          return array(
            "status" => "success",
            "key" => $key,
            "message" => "User can stay logged in!"
          );
        }
        else
        {
          echo "User has an expired Key! Boot 'em!\n";
          $query = "DELETE FROM validations WHERE username = '$username'";
          $result = $db_conn->query($query);

          return array(
            "status" => "boot",
            "message" => "User needs to be logged out!"
          );
        }
    }
  }
  return array(
      "status" => "success",
      "key" => $key,
      "message" => ""
  );
}

function getGenres()
{
    global $db_conn;
    $query = "SELECT * FROM genres";
    $result = $db_conn->query($query);
    $now = time();
    if ($result->num_rows == 0)
        goto create_genres;
    $row = $result->fetch_assoc();
    if ($row["createdAt"] + API_CACHE_DURATION > $now)
        goto destroy_genres;

    $genres = array();
    while ($row) {
        $genres[$row["id"]] = $row["name"];
        $row = $result->fetch_assoc();
    }

    return $genres;

destroy_genres:
    $query = "DELETE FROM genres";
    $result = $db_conn->query($query);

create_genres:
    $client = new rabbitMQClient("db_client.ini", "data_listen_queue", "data_listen");
    $request = array();
    $request['type'] = "genres";
    $raw_genres = $client->send_request($request)["genres"];

    $genres = array();
    foreach ($raw_genres as $genre) {
        $genres[$genre["id"]] = $genre["name"];
        $query = "INSERT INTO genres (id, name, createdAt) VALUES ($genre[id], '$genre[name]', $now)";
        $db_conn->query($query);
    }

    return $genres;
}

function getMovieFromCache($movieId)
{
    global $db_conn;
    $now = time();
    $query = "SELECT * FROM movies WHERE id='$movieId'";
    $result = $db_conn->query($query);
    if ($result->num_rows == 0)
        return false;
    $row = $result->fetch_assoc();
    if ($row["createdAt"] + API_CACHE_DURATION > $now)
	return false;
    $today = date("Y-m-d");
    $date_release = $row["release_date" ?? ""];
    $release_state = "";
    if ($date_release > $today) {
       $row["release"] = "Releases: " . $date_release;
    }

    return array(
        "id" => $row["id"],
        "title" => unescapeString($row["title"]),
        "overview" => unescapeString($row["overview"]),
        "poster_img_url" => unescapeString($row["poster_img_url"]),
	"release_date" => $row["release_date"],
	"release_state" => $release_state,
        "genre_id" => $row["genre_id"],
        "vote_average" => $row["vote_average"]
    );
}

function cacheMovie($movie)
{
    global $db_conn;

    $id = $movie["id"];
    $title = $movie['title'];
    $overview = $movie['overview'];
    $poster_img_url = "https://image.tmdb.org/t/p/w500" . $movie['poster_path'];
    $poster_img_url = $poster_img_url;
    $genre_id = -1;
    if (isset($movie["genres"]))
        $genre_id = $movie["genres"][0]["id"] ?? -1;
    if (isset($movie["genre_ids"]))
        $genre_id = $movie["genre_ids"][0] ?? -1;
    $vote_average=$movie['vote_average'];
    $release_date = $movie["release_date"];
    $today = date("Y-m-d");
    $date_release = $movie['release_date'] ?? "TBD";
    $release = "";
    if ($date_release > $today) {
       $release = "Releases: " . $date_release;
    }

    $ret = array(
        "id" => $id,
        "title" => $title,
        "overview" => $overview,
        "poster_img_url" => $poster_img_url,
	"release_date" => $release_date,
	"release_state" => $release,
        "genre_id" => $genre_id,
        "vote_average" => $vote_average
    );

    $title = escapeString($title);
    $overview = escapeString($overview);
    $poster_img_url = escapeString($poster_img_url);

    $now = time();
    $query = "SELECT * FROM movies WHERE id='$id'";
    $result = $db_conn->query($query);
    if ($result->num_rows == 0) {
        echo "Movie id $id ($title) not found in cache adding now\n";
        $query = "INSERT INTO movies (id, title, overview, genre_id, poster_img_url, release_date, vote_average, createdAt) 
                    VALUES ('$id', '$title', '$overview', $genre_id, '$poster_img_url', '$release_date', '$vote_average', $now)";
        $result = $db_conn->query($query);
        return $ret;
    }
    $row = $result->fetch_assoc();
    if ($row["createdAt"] + API_CACHE_DURATION > $now) {
        echo "Movie id $id ($title) found in cache but was expired, updating now\n";
        $query = "UPDATE movies SET createdAt=$now WHERE id='$id'";
        $result = $db_conn->query($query);
        return $ret;
    }

    return $ret;
}

function getMovie($movieId)
{
    global $db_conn;

    $movie = getMovieFromCache($movieId);
    if ($movie)
        return $movie;

    $client = new rabbitMQClient("db_client.ini", "data_listen_queue", "data_listen");
    $request = array();
    $request['type'] = "movie";
    $request['id'] = $movieId;
    $movie = $client->send_request($request);

    return cacheMovie($movie);
}

function getPopularMovies()
{
    global $db_conn;
    $query = "SELECT * FROM popular_movies";
    $popular = array();
    $result = $db_conn->query($query);
    $now = time();
    if ($result->num_rows == 0)
        goto update_popular;
    $row = $result->fetch_assoc();
    if ($row["createdAt"] + API_CACHE_DURATION > $now)
        goto delete_popular;
    echo "Retrieving popular movies from cache\n";
    while ($row) {
        array_push($popular, getMovie($row["id"]));
        $row = $result->fetch_assoc();
    }
    return $popular;

delete_popular:
    $query = "DELETE FROM popular_movies";
    $result = $db_conn->query($query);

update_popular:
    echo "Popular movies either not cached or expired, retrieving now\n";
    $client = new rabbitMQClient("db_client.ini", "data_listen_queue", "data_listen");
    $request = array();
    $request['type'] = "popular";
    $movies_data = $client->send_request($request);
    $movies = $movies_data["results"];
    foreach ($movies as $movie) {
        array_push($popular, cacheMovie($movie));
        $query = "INSERT INTO popular_movies (id, createdAt) VALUES ('$movie[id]', $now)";
        $db_conn->query($query);
    }
    return $popular;
}

function getRecommendations($username)
{
    global $db_conn;
    $query = "SELECT movie_id FROM reviews WHERE username='$username' AND score >= 7";
    $result = $db_conn->query($query);
    if ($result->num_rows == 0) {
        echo "User $username doesn't have a review with 7 or higher score, returing popular movies as recommendation\n";
        return array(
            "found_movie" => false,
            "results" => getPopularMovies($username)
        );
    }
    $row = $result->fetch_assoc();
    $movie_id = $row["movie_id"];
    $movie = getMovie($movie_id);
    $movie_title = $movie["title"];
    $genres = getGenres();

    $client = new rabbitMQClient("db_client.ini", "data_listen_queue", "data_listen");
    $request = array();
    $request['type'] = "popular_in_genre";
    $request['genre_id'] = $movie["genre_id"];
    $raw_movies = $client->send_request($request)["results"];

    $movies = array();

    foreach ($raw_movies as $movie) {
        array_push($movies, cacheMovie($movie));
    }

    return array(
        "found" => true,
        "movie_id" => $movie_id,
        "movie_title" => $movie_title,
        "results" => $movies
    );
}

function getWatchlist($user)
{
      global $db_conn;
      $query = "SELECT movie_id FROM watchlist WHERE username='$user'";
      $result = $db_conn->query($query);
      $list = [];
      while ($row = $result->fetch_assoc()) { 
          $list[] = getMovie($row["movie_id"]); 
      }
      return $list; // Added the return to fix the hang - ME
}

function getUpcoming()
{
    global $db_conn;
    $client = new rabbitMQClient("db_client.ini", "data_listen_queue", "data_listen");
    $request = array();
    $request['type'] = "upcoming";
    $raw_upcoming = $client->send_request($request);
    $upcoming = array();
    foreach ($raw_upcoming["results"] as $movie) {
        array_push($upcoming, cacheMovie($movie));
    }
    return $upcoming;
}

function addToWatchlist($user, $m_id, $m_name, $r_date)
{
      global $db_conn;
      $check = "SELECT id FROM watchlist WHERE username='$user' AND movie_id='$m_id'";
      $result = $db_conn->query($check);

      if ($result->num_rows == 0) {
        $query = "INSERT INTO watchlist (username, movie_id, movie_name, release_date) VALUES ('$user', '$m_id', '$m_name', '$r_date')";
        $db_conn->query($query);
        return array("status" => "success", "message" => "Added successfully");
      }

      return array("status" => "exists", "message" => "Already in watchlist");
}

//This function returns all reviews by user for movieID as an array! - ME
function getAllReviewsOne($username,$movieID)
{
    global $db_conn;
    $query = "SELECT username,movie_id,score, review FROM reviews WHERE movie_id = '$movieID'";
    $result = $db_conn->query($query);
	
	//var_dump($username);
	//var_dump($message);
	

    if ($result->num_rows == 0)
    {
        echo "No reviews exist for this movie!\n";
    }
    else
    {
        $reviewsArray = array();
        while ($row = $result->fetch_assoc()) 
        {
            $reviewsArray[] = $row;
        }
        return $reviewsArray;
        
        echo "Success!\n";
    }
    return array(
        "status" => "failed",
        "message" => "Internal Error!"
    );
}

//This function creates a review for a movie if it does not exist. if it does, returns fail!
function createReview($username, $message, $movieID, $rating)
{
	//to do, at somepoint sanatize message!!!!!!!!!!!!!!!!!!!!!
	//var_dump($username);
	//var_dump($message);
	//var_dump($movieID);
	
	global $db_conn;
    	$query = "SELECT * from reviews where username='$username' and movie_id ='$movieID'";
	$result = $db_conn->query($query);
	
	if ($result->num_rows == 0)
	{
		echo "No rows from movie!\n";
		
		//IMPORTANT MAKE SURE TO VERIFY WITH THE DATA THAT THE MOVIE ID EXIST!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		
		//$query = "INSERT INTO users VALUES ('$username','$password');";
		$query = "INSERT INTO reviews (username, movie_id, score, review) VALUES ('$username', '$movieID', 	$rating, '$message');";

		$result = $db_conn->query($query);

		return array (
	"status" => "success",
	"message" => "Inserted User's review into DB!"
		);
	}
	else
	{
		echo "Rows from movie!\n";
	}

  	      return array(
          "status" => "failed",
          "message" => "Internal Error or user+movie combo not exists!"
      );
}

//This function checks if a person has made a review on a movie and updates their review with what they have sent
function updateReview($username, $message, $movieID,$rating)
{
	//to do, at somepoint sanatize message!!!!!!!!!!!!!!!!!!!!!
	var_dump($username);
	var_dump($message);
	var_dump($movieID);
	var_dump($rating);
	
	global $db_conn;
    	$query = "SELECT * from reviews where username='$username' and movie_id ='$movieID'";
	$result = $db_conn->query($query);
	
	if ($result->num_rows == 0)
	{
		echo "No rows from movie! Either no reviews or movie not real!\n";
	}
	else
	{
		//echo "Rows from movie!\n";

		$query = "UPDATE reviews set review = '$message', score = $rating where username ='$username'";
		 $result = $db_conn->query($query);
		
		echo "Review should be updated!\n";
		  return array(
		    "status" => "success",
		    "message" => $message
		  );
	}

  	      return array(
          "status" => "failed",
          "message" => "Internal Error or user+movie combo not exists!"
      );
}

//This returns an array with every single user's review for each media thing
function reviewAll()
{
    global $db_conn;
    $query = "SELECT username,movie_id,score, review from reviews";
    $result = $db_conn->query($query);

    if ($result->num_rows == 0)
    {
        echo "No Movies have been reviewed!\n";
    }
    else
    {
        $reviewsArray = array();
        while ($row = $result->fetch_assoc()) 
        {
            $reviewsArray[] = $row;
        }
        return $reviewsArray;
    }
    return array(
        "status" => "failed",
        "message" => "Internal Error!"
    );
}
function higherlower($count){
	global $db_conn;
	$now=time();
	$query="SELECT * FROM movies WHERE vote_average > 1 ORDER BY rand() limit $count";
	$result=$db_conn->query($query);
	if($result->num_rows>=$count){
		echo "get movies for higher lower\n";
		while($row=$result->fetch_assoc())
            $movies[] = $row;
		return array("results"=>$movies);
	}
	echo "new movies\n";
	$client=new rabbitMQClient("db_client.ini", "data_listen_queue","data_listen");
	$request=array();
	$request['type']="higherlower";
	$request['count']= $count;
	$moviesdata=$client->send_request($request);
	$movieList=$moviesdata["results"];
	foreach ($movieList as $movie){
        var_dump($movie);
        $movies[] = cacheMovie($movie);
	}
	return array("results"=>$movies);
}

function getAllReviewsForUser($username)
{
	global $db_conn;
    $query = "SELECT * from reviews where username='$username'";
    $result = $db_conn->query($query);
    $reviews = array();
    while ($row = $result->fetch_assoc()) {
        $review = array(
            "score" => $row["score"],
            "review" => $row["review"],
            "movie" => getMovie($row["movie_id"])
        );
        array_push($reviews, $review);
    }
    return $reviews;
}

function getSearch($request) {
   global $db_conn;
   $client = new rabbitMQClient("db_client.ini", "data_listen_queue", "data_listen");
   $raw_search = $client->send_request($request);
   var_dump($raw_search);
   $search = array();
   foreach ($raw_search['results'] as $movie){
      $search[] = cacheMovie($movie);
   }
   return array("results"=>$search);
}

function requestProcessor($request)
{
  global $db_conn;  
  global $current_db;
   
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
    return "ERROR: unsupported message type";


if($current_db == 1)
{
  echo "Using DB 1!\n";
  //We are using DB 1
  //Ping DB 1 and check if it is alive
  //If it is alive, continue using it and send a copy of its DB to DB 2
  //If it is dead, swap to using DB 2 then continue - assume DB 2 is alive

  //Current testing purposes, DB 1 is localhost
  //DB 2 is ME's DB at IP 100.111.93.122

  //Due to current being localhost, no need to ping itself, but make a script here to ping the real DB 1 later
  //Ping self
  
  //Check if DB1 is online
  $output;
  $output = shell_exec("scripts/pingDB1.sh");
  $output = trim($output);
  
  if($output == "online!")
  {
     //Send a copy of the DB to DB2
     //$db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);
     shell_exec("scripts/zip.sh");
  }
  else
  {
     //DB 1 is offline, swap to 2
     $current_db = 2;
     $config = parse_ini_file('db_mysql2.ini');
     $db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);
  }
  
}
else
{
   echo "Using DB 2!\n";
   //We are using DB 2 invert above comments
   $output;
   $output = shell_exec("scripts/aliveTest.sh");
   $output = trim($output);

   if($output == "online!")
   {
     //DB 2 is online, send a copy to DB 1
     //This script just sends it to DB 2, currently DB 1 is localhost
     shell_exec("scripts/zip.sh");
   }
   else
   {
     //DB 2 is offline, swap to DB 1
     $current_db = 1;
     $config = parse_ini_file('db_mysql1.ini');
     $db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);
   }
}

  

  try {
      switch ($request['type'])
      {
        case "login":
          return doLogin($request['username']);
        case "register":
          return doRegister($request['username'],$request['email'],$request['password']);
        case "get_email":
          return getEmail($request['username']);
        case "validate_session":
          return doValidate($request['username']);
        case "movie":
          return getMovie($request['id']);
        case "popular":
          return getPopularMovies();
        case "recommend":
          return getRecommendations($request["username"]);
        case "watchlist":
          return getWatchlist($request["username"]);
        case "upcoming":
          return getUpcoming();
        case "add_watchlist":
          return addToWatchlist($request["username"], $request["movie_id"], $request["movie_name"], $request["release_date"]);
        case "review_movie":
          return updateReview($request['username'],$request['message'],$request['movieID'],$request['rating']);
        case "reviewAll":
          return reviewAll();
        case "getAllReviewsOne":
          return getAllReviewsOne($request['username'],$request['movieID']);
        case "get_all_reviews_for_user":
          return getAllReviewsForUser($request['username']);
        case "createReview":
          return createReview($request['username'],$request['message'],$request['movieID'],$request['rating']);
        case "higherlower":
          return higherlower($request['count']);
        case "search":
          return getSearch($request);
          
         default:
          return "Error: Sent an invalid request type!";
      }
      return array("returnCode" => '0', 'message'=>"Server received request and processed");
  } catch (Exception $e) {
      echo "PHP ERROR: " . $e->getMessage() . "\n";
      $i = 1;
      foreach ($e->getTrace() as $call) {
          $file = $call["file"] ?? "unknown";
          $line = $call["line"] ?? "0";
          $function = $call["function"] ?? "unknown";
          $class = $call["class"] ?? "";
          $type = $call["type"] ?? "";
          echo "  #$i $file($line): $class$type$function\n";
          $i++;
      }
      return array("status" => "failed");
  }
}

$server = new rabbitMQServer("db_server.ini");
$server->process_requests('requestProcessor');
?>
