#!/bin/php
<?php
require_once('rabbitMQLib.inc');

$config = parse_ini_file('db_mysql.ini');

$db_conn = new mysqli($config["MYSQL_HOST"],$config["MYSQL_USER"],$config["MYSQL_PASS"],$config["MYSQL_DB"]);

define("API_CACHE_DURATION", 60*60*24);

function doLogin($username, $password)
{
  global $db_conn;
  $query = "SELECT username, password FROM users WHERE username='$username'";
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
  );
}

function doRegister($username,$password)
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
  $query = "INSERT INTO users (username, password) VALUES ('$username','$password');";
  $result = $db_conn->query($query);
  $arr = doValidate($username);

  return array(
    "status" => "success",
    "key" => $arr["key"],
    "message" => ""
  );
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
    $client = new rabbitMQClient("db_client.ini", "data_queue", "data");
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

function getMovieInfo($movieId)
{
    global $db_conn;
    if (!isset($movieId)) {
        echo "You made a dumb mistake on line " . __LINE__ . "\n";
        return;
    }
    $query = "SELECT * FROM movies WHERE id='$movieId'";
    $result = $db_conn->query($query);
    $now = time();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["createdAt"] + API_CACHE_DURATION > $now) {
            echo "Movie id $movieId ($row[title]) found in cache\n";
            return array(
                "title" => $row['title'],
                "overview" => $row['overview'],
                "poster_img_url" => $row['poster_img_url'],
                "genre_id" => $row["genre_id"]
            );
        }
    }

    $client = new rabbitMQClient("db_client.ini", "data_queue", "data");
    $request = array();
    $request['type'] = "movie";
    $request['id'] = $movieId;
    $movie = $client->send_request($request);

    $title = htmlspecialchars($movie['title']);
    $overview = htmlspecialchars($movie['overview']);
    $poster_img_url = "https://image.tmdb.org/t/p/w500" . $movie['poster_path'];
    $genres = $movie['genres'];
    $genre_id = $genres[0]["id"];

    echo "Movie id $movieId ($title) not found in cache or expired, adding now\n";

    $query = "INSERT INTO movies (id, title, overview, genre_id, poster_img_url, createdAt) 
                VALUES ('$movieId', '$title', '$overview', $genre_id, '$poster_img_url', $now)";
    $result = $db_conn->query($query);

    // verify cache was successful here

    return array(
        "title" => $title,
        "overview" => $overview,
        "poster_img_url" => $poster_img_url,
        "genre_id" => $genre_id
    );
}

function getPopularMovies($count)
{
    global $db_conn;
    $query = "SELECT * FROM popular_movies";
    $popular = array();
    $result = $db_conn->query($query);
    $now = time();
    if ($result->num_rows == 0)
        goto update_popular;
    $row = $result->fetch_assoc();
    if ($row["createdAt"] + API_CACHE_DURATION < $now)
        goto delete_popular;
    echo "Retrieving popular movies from cache\n";
    while ($row) {
        var_dump($row);
        array_push($popular, getMovieInfo($row["id"]));
        $row = $result->fetch_assoc();
    }
    return $popular;

delete_popular:
    $query = "DELETE FROM popular_movies";
    $result = $db_conn->query($query);
    // verify success here

update_popular:
    echo "Popular movies either not cached or expired, retrieving now\n";
    $client = new rabbitMQClient("db_client.ini", "data_queue", "data");
    $request = array();
    $request['type'] = "popular";
    $request['count'] = 10;
    $movies_data = $client->send_request($request);
    $movies = $movies_data["results"];
    foreach ($movies as $movie) {
        $query = "INSERT INTO popular_movies (id, createdAt) VALUES ('$movie[id]', $now)";

        // this is lazy way of doing it, it does many redundant data api calls
        // can improve this by updating the movies table from data retrieved from the popular data api call
        array_push($popular, getMovieInfo($movie['id']));
        $db_conn->query($query);
    }

    $popular = $movies_data;
    return $popular;
}

function getRecommendations($username)
{
    global $db_conn;
    $query = "SELECT movie_id FROM reviews WHERE username='$username' AND score >= 7";
    $result = $db_conn->query($query);
    if ($result->num_rows == 0) {
        echo "User $username doesn't have a review with 7 or higher score, returing popular movies as recommendation\n";
        return getPopularMovies($username);
    }
    $row = $result->fetch_assoc();
    $movie_id = $row["movie_id"];
    $movie = getMovieInfo($movie_id);
    $genres = getGenres();
    var_dump($movie);

    return array(
        "genre" => $genres[$movie["genre_id"]]
    );
}

function getWatchlist($user)
{
      global $db_conn;
      $user = $request['username'];
      $query = "SELECT movie_id, movie_name FROM watchlist WHERE username='$user'";
      $result = $db_conn->query($query);
      $list = [];
      while ($row = $result->fetch_assoc()) { 
          $list[] = $row; 
      }
      return $list; // Added the return to fix the hang - ME
}

function addToWatchlist($user, $m_id, $m_name)
{
      global $db_conn;
      // Check for duplicates
      $check = "SELECT id FROM watchlist WHERE username='$user' AND movie_id='$m_id'";
      $result = $db_conn->query($check);

      if ($result->num_rows == 0) {
        $query = "INSERT INTO watchlist (username, movie_id, movie_name) VALUES ('$user', '$m_id', '$m_name')";
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

function requestProcessor($request)
{
  global $db_conn;     
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "register":
      return doRegister($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['username']);
    case "movie":
      return getMovieInfo($request['id']);
    case "popular":
      return getPopularMovies($request['count']);
    case "recommend":
      return getRecommendations($request["username"]);
    case "watchlist":
      return getWatchlist($request["username"]);
    case "add_watchlist":
      return addToWatchlist($request["username"], $request["movie_id"], $request["movie_name"]);
    case "review_movie":
      return updateReview($request['username'],$request['message'],$request['movieID'],$request['rating']);
    case "reviewAll":
      return reviewAll();
    case "getAllReviewsOne":
      return getAllReviewsOne($request['username'],$request['movieID']);
    case "createReview":
	  return createReview($request['username'],$request['message'],$request['movieID'],$request['rating']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("db_server.ini");
$server->process_requests('requestProcessor');
?>
