<script>
  if(!sessionStorage.getItem("username")){
    window.location.href="login.html";
  }
</script>
<?php
require_once('../rabbitMQLib.inc');
include('../log.inc');
$client=new rabbitMQClient("../web_client.ini", "db_listen_queue","db");
$request=array();
$request['type']='higherlower';
$request['count']=6;
$response=$client->send_request($request);
if ($response == false){
	maddLog("higher lower request failed in web");
	return array("status"=>"failed");
}
$movies=$response["results"];
?>

<?php include "header.php"; ?>
<body class="higherlower-body">
  <?php include "navbar.php"; ?>

  <main class="container content-wrapper text-center">
    <h1 style="font-size:40px; color:#FF5E5B; font-weight: bold;">Higher or Lower?</h1>
    <p class="lead" style="color: white;">Guess which movie or tv show is rated the highest!</p>
    <p style="font-size:15px; padding:10px; color: #ccc;">Getting a question correct earns points, but incorrect will deduct them</p>

    <div class="row" style="margin-top: 30px;">
      <div class="col-xs-6">
        <div id="movie1" class="thumbnail" style="border: 5px solid #66ff00; height: 350px; background: black;"></div>
      </div>
      <div class="col-xs-6">
        <div id="movie2" class="thumbnail" style="height: 350px; background: black;"></div>
      </div>
    </div>
    <div class="row" style="margin-bottom: 40px;">
      <div class="col-xs-12">
        <button id="higher1" class="btn btn-primary btn-lg">Higher</button>
        <button id="lower1" class="btn btn-danger btn-lg">Lower</button>
        <div id="result1"></div>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-6">
        <div id="movie3" class="thumbnail" style="border: 5px solid #66ff00; height: 350px; background: black;"></div>
      </div>
      <div class="col-xs-6">
        <div id="movie4" class="thumbnail" style="height: 350px; background: black;"></div>
      </div>
    </div>
    <div class="row" style="margin-bottom: 40px;">
      <div class="col-xs-12">
        <button id="higher2" class="btn btn-primary btn-lg">Higher</button>
        <button id="lower2" class="btn btn-danger btn-lg">Lower</button>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-6">
        <div id="movie5" class="thumbnail" style="border: 5px solid #66ff00; height: 350px; background: black;"></div>
      </div>
      <div class="col-xs-6">
        <div id="movie6" class="thumbnail" style="height: 350px; background: black;"></div>
      </div>
    </div>
    <div class="row" style="margin-bottom: 40px;">
      <div class="col-xs-12">
        <button id="higher3" class="btn btn-primary btn-lg">Higher</button>
        <button id="lower3" class="btn btn-danger btn-lg">Lower</button>
      </div>
    </div>

    <div class="well" style="background: rgba(0,0,0,0.5); border-color: #FF5E5B;">
      <h2 style="color: white;">Current Score: <span id="score" style="color:#FF5E5B;">0</span></h2>
    </div>
  </main>

<script>
const movies=<?php echo json_encode($movies); ?>;
let score=0;

for (let i = 1; i <= 6; i++) {
  document.getElementById(`movie${i}`).innerHTML = '<img src="' + movies[i-1].poster_img_url + '" class="img-responsive" style="height: 100%; width: 100%; object-fit: contain;">';
}

const btnH1=document.getElementById("higher1");
const btnL1=document.getElementById("lower1");
const btnH2=document.getElementById("higher2");
const btnL2=document.getElementById("lower2");
const btnH3=document.getElementById("higher3");
const btnL3=document.getElementById("lower3");

btnH1.addEventListener("click", function(){ 
  verdict(movies[0].vote_average,movies[1].vote_average);
  document.getElementById("movie1").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[0].vote_average + '</h2>';
  document.getElementById("movie2").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[1].vote_average + '</h2>';
  btnH1.disabled=true;
  btnL1.disabled=true;
});

btnL1.addEventListener("click",function(){
  verdict(movies[1].vote_average,movies[0].vote_average);
  document.getElementById("movie1").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[0].vote_average + '</h2>';
  document.getElementById("movie2").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[1].vote_average + '</h2>';
  btnH1.disabled=true;
  btnL1.disabled=true;
});

btnH2.addEventListener("click",function(){
  verdict(movies[2].vote_average,movies[3].vote_average);
  document.getElementById("movie3").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[2].vote_average + '</h2>';
  document.getElementById("movie4").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[3].vote_average + '</h2>';
  btnH2.disabled=true;
  btnL2.disabled=true;
});

btnL2.addEventListener("click",function(){
  verdict(movies[3].vote_average,movies[2].vote_average);
  document.getElementById("movie3").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[2].vote_average + '</h2>';
  document.getElementById("movie4").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[3].vote_average + '</h2>';
  btnH2.disabled=true;
  btnL2.disabled=true;
});

btnH3.addEventListener("click",function(){
  verdict(movies[4].vote_average,movies[5].vote_average);
  document.getElementById("movie5").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[4].vote_average + '</h2>';
  document.getElementById("movie6").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[5].vote_average + '</h2>';
  btnH3.disabled=true;
  btnL3.disabled=true;
});

btnL3.addEventListener("click",function(){
  verdict(movies[5].vote_average,movies[4].vote_average);
  document.getElementById("movie5").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[4].vote_average + '</h2>';
  document.getElementById("movie6").innerHTML = '<h2 style="color:white; margin-top:140px;">' + movies[5].vote_average + '</h2>';
  btnH3.disabled=true;
  btnL3.disabled=true;
});

function verdict(rating1, rating2){
  if(rating1 > rating2){
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

