<!DOCTYPE html>
<html>
<body>
<style>
.gameArea{
	display:grid;
	grid-template-columns:20% 20%;
}
.movieArea{
	width:150px;
	height:150px;
}
</style>
<h1>Higher or Lower?</h1>
<p>Guess which movie or tv show is rated the highest!</p>
<div id="result"></div>
<div class="gameArea" id="gameArea">
	<div id="movie1" class="movieArea" style="background-color:blue;"></div>
	<div id="movie2" class="movieArea" style="background-color:red;"></div>
</div>
	<div id="result1"></div>
	<button id="higher1">Higher</button>
	<button id="lower1">Lower</button>
<div class="gameArea" id="gameArea">
	<div id="movie3" class="movieArea" style="background-color:blue;"></div>
	<div id="movie4" class="movieArea" style="background-color:red;"></div>
</div>
	<button id="higher2">Higher</button>
	<button id="lower2">Lower</button>
<div class="gameArea" id="gameArea">
	<div id="movie5" class="movieArea" style="background-color:blue;"></div>
	<div id="movie6" class="movieArea" style="background-color:red;"></div>
</div>
	<button id="higher3">Higher</button>
	<button id="lower3">Lower</button>
<p>Score:</p>
<p id="score">0</p>
<script>
let score=0;
const movies=[
	{title:"IT", rating: 8.1},
	{title:"Jaws", rating: 10},
	{title:"Chips", rating: 3.1},
	{title:"Rings", rating: 1.1},
	{title:"Mouse", rating:7},
	{title:"Door", rating:2}
];
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
});
btnL1.addEventListener("click",function(){
	verdict(movies[0].rating,movies[1].rating);
	document.getElementById("movie1").innerHTML=movies[0].rating;
	document.getElementById("movie2").innerHTML=movies[1].rating;
});
btnH2.addEventListener("click",function(){
	verdict(movies[2].rating,movies[3].rating);
	document.getElementById("movie3").innerHTML=movies[2].rating;
	document.getElementById("movie4").innerHTML=movies[3].rating;
});
btnL2.addEventListener("click",function(){
	verdict(movies[2].rating,movies[3].rating);
	document.getElementById("movie3").innerHTML=movies[2].rating;
	document.getElementById("movie4").innerHTML=movies[3].rating;
});
btnH3.addEventListener("click",function(){
	verdict(movies[4].rating,movies[5].rating);
	document.getElementById("movie5").innerHTML=movies[4].rating;
	document.getElementById("movie6").innerHTML=movies[5].rating;
});
btnL3.addEventListener("click",function(){
	verdict(movies[4].rating,movies[5].rating);
	document.getElementById("movie5").innerHTML=movies[4].rating;
	document.getElementById("movie6").innerHTML=movies[5].rating;
});
function verdict(rating1, rating2){
	if(rating1>rating2){
		document.getElementById("result").innerHTML="Correct";
		score++;
		document.getElementById("score").innerHTML=score;
	}	
	else{
		document.getElementById("result").innerHTML="Incorrect";
		score--;
		document.getElementById("score").innerHTML=score;
	}
}
</script>
<?php
//require_once('../rabbitMQLib.inc');
//$client=new rabbitMQClient("web_client.ini", "data_queue", "data");
//$request['type']="title";
//$response =$client->send_request($request);
//echo $request;
?>
</body>
</html>

