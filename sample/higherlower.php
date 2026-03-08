<?php
require_once('../rabbitMQLib.inc');
$client=new rabbitMQClient("web_client.ini", "db_web_queue","db");
$request=array();
$request['type']='higherlower';
$request['count']=6;
$response=$client->send_request($request);
$movies=$response["results"];
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="madd.css">
<body class="higherlower-body">
<h1 style="font-size:40px; color:#FF5E5B;">Higher or Lower?</h1>
<p>Guess which movie or tv show is rated the highest!</p>
<p style="font-size:15px; padding:10px;">Getting a question correct earns points, but incorrect will deduct them</p>
<div class="gameArea">
	<div id="movie1" class="movieAreaA"></div>
	<div id="movie2" class="movieAreaB"></div>
</div>
	<div id="result1"></div>
	<button id="higher1" class="higherlowerBttns" >Higher</button>
	<button id="lower1" class="higherlowerBttns" >Lower</button>
<div class="gameArea">
	<div id="movie3" class="movieAreaA"></div>
	<div id="movie4" class="movieAreaB"></div>
</div>
	<button id="higher2" class="higherlowerBttns" >Higher</button>
	<button id="lower2" class="higherlowerBttns" >Lower</button>
<div class="gameArea">
	<div id="movie5" class="movieAreaA"></div>
	<div id="movie6" class="movieAreaB"></div>
</div>
	<button id="higher3" class="higherlowerBttns" >Higher</button>
	<button id="lower3" class="higherlowerBttns" >Lower</button>
<p>Score:</p>
<p id="score">0</p>
<script>
const movies=<?php echo json_encode($movies); ?>;
let score=0;
document.getElementById("movie1").innerHTML='<img src ="' + movies[0].poster_img_url + '">';
document.getElementById("movie2").innerHTML='<img src ="' + movies[1].poster_img_url + '">';
document.getElementById("movie3").innerHTML='<img src ="' + movies[2].poster_img_url + '">';
document.getElementById("movie4").innerHTML='<img src ="' + movies[3].poster_img_url + '">';
document.getElementById("movie5").innerHTML='<img src ="' + movies[4].poster_img_url + '">';
document.getElementById("movie6").innerHTML='<img src ="' + movies[5].poster_img_url + '">';
const btnH1=document.getElementById("higher1");
const btnL1=document.getElementById("lower1");
const btnH2=document.getElementById("higher2");
const btnL2=document.getElementById("lower2");
const btnH3=document.getElementById("higher3");
const btnL3=document.getElementById("lower3");
btnH1.addEventListener("click", function(){ 
	verdict(movies[0].vote_average,movies[1].vote_average);
	document.getElementById("movie1").innerHTML=movies[0].vote_average;
	document.getElementById("movie2").innerHTML=movies[1].vote_average;
	btnH1.disabled=true;
	btnL1.disabled=true;
});
btnL1.addEventListener("click",function(){
	verdict(movies[1].vote_average,movies[0].vote_average);
	document.getElementById("movie1").innerHTML=movies[0].vote_average;
	document.getElementById("movie2").innerHTML=movies[1].vote_average;
	btnH1.disabled=true;
	btnL1.disabled=true;
});
btnH2.addEventListener("click",function(){
	verdict(movies[2].vote_average,movies[3].vote_average);
	document.getElementById("movie3").innerHTML=movies[2].vote_average;
	document.getElementById("movie4").innerHTML=movies[3].vote_average;
	btnH2.disabled=true;
	btnL2.disabled=true;
});
btnL2.addEventListener("click",function(){
	verdict(movies[3].vote_average,movies[2].vote_average);
	document.getElementById("movie3").innerHTML=movies[2].vote_average;
	document.getElementById("movie4").innerHTML=movies[3].vote_average;
	btnH2.disabled=true;
	btnL2.disabled=true;
});
btnH3.addEventListener("click",function(){
	verdict(movies[4].vote_average,movies[5].vote_average);
	document.getElementById("movie5").innerHTML=movies[4].vote_average;
	document.getElementById("movie6").innerHTML=movies[5].vote_average;
	btnH3.disabled=true;
	btnL3.disabled=true;
});
btnL3.addEventListener("click",function(){
	verdict(movies[5].vote_average,movies[4].vote_average);
	document.getElementById("movie5").innerHTML=movies[4].vote_average;
	document.getElementById("movie6").innerHTML=movies[5].vote_average;
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
