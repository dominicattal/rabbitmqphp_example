<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Watchlist - MADD FOR MOVIES</title>
    <link rel="stylesheet" href="madd.css">
</head>
<script>
//This if statement checks if a user is logged in
//If not, dumps them at the log in screen
  //At some point this might need to be changed to check for session info aswell - ME
if(!sessionStorage.getItem("username"))
{

  window.location.href = "login.html";
}
</script>
