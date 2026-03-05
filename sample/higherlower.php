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
<button id="higher">Higher</button>
<button id="lower">Lower</button>
<button id="next">Next</button>
<script>
const btnH=document.getElementById("higher");
btnH.addEventListener("click", 
function(){ 
	document.getElementById("movie1").innerHTML="hello";
}
);
const btnL=document.getElementById("lower");
btnL.addEventListener("click",
function(){
	document.getElementById("movie2").innerHTML="loser";
}
);
const btnN=document.getElementById("next");
btnN.addEventListener("click",
function(){

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

