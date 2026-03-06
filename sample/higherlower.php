<!DOCTYPE html>
<html>
<body>
<style>
.gameArea{
	display:grid;
	grid-template-columns:25% 25%;
}
.movieArea{
	width: 100px;
	height:100px;
}
</style>
<h1>Higher or Lower?</h1>
<p>Guess which movie or tv show is rated the highest!</p>
<div class="gameArea" id="gameArea">
	<div id="movie1" class="movieArea" style="background-color:blue;"></div>
	<div id="movie2" class="movieArea" style="background-color:red;"></div>
</div>
<div id="result1"></div>
<button id="higher1">Higher</button>
<button id="lower1">Lower</button>
<div id="movie3" style="background-color:blue;">
</div>
<div id="movie4" style="background-color:red;">
</div>
<button id="higher2">Higher</button>
<button id="lower2">Lower</button>
<button id="next2">Next</button>
<div id="movie5" style="background-color:blue;">
</div>
<div id="movie6" style="background-color:red;">
</div>
<button id="higher3">Higher</button>
<button id="lower3">Lower</button>
<button id="next3">Next</button>
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
const btnH=document.getElementById("higher1");
btnH.addEventListener("click", 
function(){ 
	verdict();
	document.getElementById("movie1").innerHTML=movies[0].rating;
}
);
const btnL=document.getElementById("lower1");
btnL.addEventListener("click",
function(){
	verdict();
	document.getElementById("movie2").innerHTML=movies[1].rating;
}
);
function verdict(){
	if(movies[0].rating>movies[1].rating){
		document.getElementById("result1").innerHTML="Correct";
		score++;
		document.getElementById("score").innerHTML=score;
	}	
	else{
		document.getElementById("result1").innerHTML="Incorrect";
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

