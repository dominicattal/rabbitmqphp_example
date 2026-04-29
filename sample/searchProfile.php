<?php
require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "db_listen_queue", "db_listen");
$searchProfile=$_GET['search'] ?? null;
var_dump($searchProfile);
$request=array();
$profiles=[];
$request['type']="searchProfile";
$request['query']=$searchProfile;
$response=$client->send_request($request);
$profiles=$response['results'];
var_dump($response);
?>
<?php// include "header.php"; ?>
<body class="home-body">
<?php include "navbar.php"; ?>
<h1 style="font-size:50px; color:#F9870A;">Search Profiles</h1>
<form action="" method="GET">
	<div class="searchbar-container">
		<input type="text" name="search" value="" placeholder="Profile name..."/>
		<button type="submit" >Find Profile</button>
	</div>
</form>
<?php var_dump($profiles);
var_dump($response);?>
<?php foreach($profiles as $profile):?>
	<div class="profileBox" style="padding:20px; display:block; font-size:30px; font-color:#000000;">
	<p> <?php echo htmlspecialchars($profile["username"]);?> </p>
	</div>
<?php endforeach; ?>
	     
</div>
</body>
