<?php
require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "db_listen_queue", "db_listen");
$request=array();
$request['type']="searchProfile";
$response=$client->send_request($request);
include "navbar.php"
?>
<body class="searchProfile-body">
<?php include "header.php"?>
<h1 style="font-size:50px; color:#FFFFFF;">Results:</h1>
<div class="profileBox">
 <?php echo $response ?>
</div>
</body>
