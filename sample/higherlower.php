<?php
session_start();
require_once('../rabbitMQLib.inc');
$client=new rabbitMQClient("web_client.ini", "db_queue","db");
$request=array();
$request=['type']="rated";
$request=['count']=6;
$response=$client->send_request($request);
$movies=$response["results"];
//shuffle($movies);
foreach($movies as $movie):
	$movieId=$movie['id'];
	$title=htmlspecialchars($movie['title']);
	$rating=$movie['rating'];
	$poster="https://image.tmdb.org/t/p/w500" . $movie['poster_path'];

?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="madd.css">
<body class="higherlower-body">
<h1 style="font-size:40px; color:#FF5E5B;">Higher or Lower?</h1>
<p>Guess which movie or tv show is rated the highest!</p>
<p style="font-size:10px; padding:10px;">Getting a question correct earns points, but incorrect will deduct them</p>
<div class="gameArea">
	<div id="movie1" class="movieAreaA"></div>
	<div id="movie2" class="movieAreaB"></div>
</div>
	<div id="result1"></div>
	<button id="higher1">Higher</button>
	<button id="lower1">Lower</button>
<div class="gameArea" id="gameArea">
	<div id="movie3" class="movieAreaA"></div>
	<div id="movie4" class="movieAreaB"></div>
</div>
	<button id="higher2">Higher</button>
	<button id="lower2">Lower</button>
<div class="gameArea" id="gameArea">
	<div id="movie5" class="movieAreaA"></div>
	<div id="movie6" class="movieAreaB"></div>
</div>
	<button id="higher3">Higher</button>
	<button id="lower3">Lower</button>
<p>Score:</p>
<p id="score">0</p>
<script>
let score=0;
const movies=<?php echo json_encode($movies); ?>;
document.getElementById("movie1").innerHTML=movies[0].title;
document.getElementById("movie2").innerHTML=movies[1].title;
document.getElementById("movie3").innerHTML=movies[2].title;
document.getElementById("movie4").innerHTML=movies[3].title;
document.getElementById("movie5").innerHTML=movies[4].title;
document.getElementById("movie6").innerHTML=movies[5].title;
const btnH1=document.getElementById("higher1");
const btnL1=document.getElementById("lower1");
const btnH2=document.getElementById("higher2");
const btnL2=document.getElementById("lower2");
const btnH3=document.getElementById("higher3");
const btnL3=document.getElementById("lower3");
btnH1.addEventListener("click", function(){ 
	verdict(movies[0].rating,movies[1].rating);
	document.getElementById("movie1").innerHTML=movies[0].rating;
	document.getElementById("movie2").innerHTML=movies[1].rating;
	btnH1.disabled=true;
	btnL1.disabled=true;
});
btnL1.addEventListener("click",function(){
	verdict(movies[1].rating,movies[0].rating);
	document.getElementById("movie1").innerHTML=movies[0].rating;
	document.getElementById("movie2").innerHTML=movies[1].rating;
	btnH1.disabled=true;
	btnL1.disabled=true;
});
btnH2.addEventListener("click",function(){
	verdict(movies[2].rating,movies[3].rating);
	document.getElementById("movie3").innerHTML=movies[2].rating;
	document.getElementById("movie4").innerHTML=movies[3].rating;
	btnH2.disabled=true;
	btnL2.disabled=true;
});
btnL2.addEventListener("click",function(){
	verdict(movies[3].rating,movies[2].rating);
	document.getElementById("movie3").innerHTML=movies[2].rating;
	document.getElementById("movie4").innerHTML=movies[3].rating;
	btnH2.disabled=true;
	btnL2.disabled=true;
});
btnH3.addEventListener("click",function(){
	verdict(movies[4].rating,movies[5].rating);
	document.getElementById("movie5").innerHTML=movies[4].rating;
	document.getElementById("movie6").innerHTML=movies[5].rating;
	btnH3.disabled=true;
	btnL3.disabled=true;
});
btnL3.addEventListener("click",function(){
	verdict(movies[5].rating,movies[4].rating);
	document.getElementById("movie5").innerHTML=movies[4].rating;
	document.getElementById("movie6").innerHTML=movies[5].rating;
	btnH3.disabled=true;
	btnL3.disabled=true;
});
function verdict(rating1, rating2){
	if(rating1>rating2){
		alert("Correct");
		score++;
		document.getElementById("score").innerHTML=score;
	}	
	else{
		alert("Incorrect!");
		score--;
		document.getElementById("score").innerHTML=score;
	}
}
</script>
</body>
</html>
