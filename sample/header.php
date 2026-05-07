<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <title>MADD for Movies</title>
  <meta charset="utf-8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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

<!-- OLD CODE
<head>
    <meta charset="UTF-8">
    <title>Your Watchlist - MADD FOR MOVIES</title>
    <link rel="stylesheet" href="madd.css">
</head>
<script>
//This if statement checks if a user is logged in
//If not, dumps them at the log in screen
  //At some point this might need to be changed to check for session info aswell - ME
//if(!sessionStorage.getItem("username"))
//{
//
//  window.location.href = "login.html";
//}
</script>
-->

