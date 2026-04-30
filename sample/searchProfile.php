<?php
require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "db_listen_queue", "db_listen");
$searchProfile=$_GET['search'] ?? null;
$request=array();
$profiles=[];
$request['type']="searchProfile";
$request['query']=$searchProfile;
$response=$client->send_request($request);
$profiles=$response['results'];
?>
<?php include "header.php"; ?>
<body class="home-body">
<?php include "navbar.php"; ?>
<h1 style="font-size:50px; color:#F9870A;">Search Profiles</h1>
<form action="" method="GET">
	<div class="searchbar-container">
		<input type="text" name="search" value="" placeholder="Profile name..."/>
		<button type="submit" >Find Profile</button>
	</div>
</form>
<!--To debug. checks whats in search <?php var_dump($profiles); var_dump($response);?> -->
<div class="results" style="font-size:30px;">
	<p style="font-size:50px; font-weight:bold; color:#F9870A;">

		<?php if (empty($profiles)){ 
			echo "No results";
		}	 
      		      else
			echo "Results:";
		?>
	</p>
</div>
<?php foreach($profiles as $profile):?>
	<div class="profileBox" style="padding:20px; display:block;">
		<a href="profile.php?username=<?php echo $profile['username'];?>&email=<?php echo $profile['email'];?>">
		<p style="font-size:30px; font-weight:bold; color:#F9870A;">
			<?php echo htmlspecialchars($profile['username']);?>
		</p>
		</a>
	</div>
<?php endforeach; ?>	     
</div>
</body>
