<?php
require_once('../rabbitMQLib.inc');
$client = new rabbitMQClient("web_client.ini", "db_listen_queue", "db_listen");
$searchProfile = $_GET['search'] ?? null;
$request = array();
$profiles = [];
$request['type'] = "searchProfile";
$request['query'] = $searchProfile;
$response = $client->send_request($request);
$profiles = $response['results'] ?? [];
?>

<?php include "header.php"; ?>
<body class="home-body">
  <?php include "navbar.php"; ?>

  <main class="container content-wrapper">
    <h1 class="text-center section-title" style="color:#F9870A;">Search Profiles</h1>

    <div class="row" style="margin-bottom: 30px;">
      <div class="col-md-6 col-md-offset-3">
        <form action="" method="GET">
          <div class="input-group">
            <input type="text" name="search" class="form-control input-lg" placeholder="Profile name..." value="<?php echo htmlspecialchars($searchProfile); ?>"/>
          </div>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <h2 style="font-weight:bold; color:#F9870A; margin-bottom: 20px;">
          <?php if (empty($profiles) && $searchProfile !== null): ?>
            No results
          <?php elseif ($searchProfile !== null): ?>
            Results:
          <?php endif; ?>
        </h2>
      </div>
    </div>

    <div class="row">
      <?php foreach($profiles as $profile): ?>
        <div class="col-sm-6 col-md-3">
          <a href="profile.php?username=<?php echo urlencode($profile['username']);?>&email=<?php echo urlencode($profile['email']);?>" style="text-decoration: none;">
            <div class="thumbnail text-center" style="padding:20px; border: 2px solid #F9870A; background-color: white; height: 180px;">
              <span class="glyphicon glyphicon-user" style="font-size: 50px; color: #F9870A; margin-bottom: 10px;"></span>
              <h4 style="font-weight:bold; color:#000;">
                @<?php echo htmlspecialchars($profile['username']); ?>
              </h4>
              <p class="text-muted">View Profile</p>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>

