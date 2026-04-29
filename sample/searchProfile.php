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
		<input text="text" name="search" value="" placeholder="Profile name..."/>
		<button type="submit" >Find Profile</button>
	</div>
</form>
<div class="profile">
	<?php echo $profiles;?>
	<?php foreach($profiles as $user):
		echo $user;
		echo "HALLO";
	      endforeach;
	?>
</div>
</body>
