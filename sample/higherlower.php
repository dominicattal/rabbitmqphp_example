<!DOCTYPE html>
<html>
<body>
<style>
.gameArea {
	display:grid;
	grid-template-columns:25% 25%;
}
.sad1{
width:100px;
height:100px;
}
.sad2{
width:100px;
height:100px;
}
</style>
<h1>Higher or Lower?</h1>
<p>Guess which movie or tv show is rated the highest!</p>
<div class="gameArea" id="gameArea">
	<div id="movie1" style="background-color:blue;">
		<img src="https://www.cambridge.org/elt/blog/wp-content/uploads/2019/07/Sad-Face-Emoji.png" class="sad2">
	</div>
	<div id="movie2" style="background-color:red;">
	<img id="sad1" src="https://www.cambridge.org/elt/blog/wp-content/uploads/2019/07/Sad-Face-Emoji.png" class="sad1">
	</div>
</div>
<button id="higher1">Higher</button>
<button id="lower1">Lower</button>
<button id="next1">Next</button>
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
<script>
const btnH=document.getElementById("higher1");
btnH.addEventListener("click", 
function(){ 
	document.getElementById("movie1").innerHTML="hello";
}
);
const btnL=document.getElementById("lower1");
btnL.addEventListener("click",
function(){
	document.getElementById("movie2").innerHTML="loser";
}
);
const btnN=document.getElementById("next1");
btnN.addEventListener("click",
function(){
	document.getElementById("gameArea").innerHTML=verdict();
}
);
function verdict(){
	//If element higher was clicked, and api of movie 1 > rating than movie 2,say correct, if false, incorrect
	
}
=======
	document.getElementById("gameArea").innerHTML="new movies";
}
);

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

