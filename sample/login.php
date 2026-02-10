<form action="login_handler.php" method="post" id="login-form">
  <div>
    <label for="username">Userusername</label>
    <input type="text" name="username" id="username" required />
  </div>
  <div>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required minlength="8"/>
  </div>
  <div>
    <input type="submit" value="Submit" />
  </div>
</form>

<?php
session_start();
if (isset($_SESSION["login_response"])) {
  echo "<p>$_SESSION[login_response]</p>";
  unset($_SESSION["login_response"]);
}
?>
