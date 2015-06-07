<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Include database login information
include "secret.php";

// Connect to database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $db_username, $db_pw, $db_username);

// Check connection
if(!$mysqli || $mysqli->connect_errno)
{
  echo "There was a problem connecting to the server.";
}

// If user did not register, redirect back to registration
if(empty($_POST))
{
  header("location: register.php");
  exit();
}

// Otherwise, send registration to database
else
{
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if(!($stmt = $mysqli->prepare("INSERT INTO user(username, email, password) VALUES (?, ?, ?)")))
  {
    echo "There was an error setting up your account.";
  }

  if(!($stmt->bind_param("ssss", $username, $email, $password)))
  {
    echo "There was an error setting up your account.";
  }

  if(!$stmt->execute())
  {
    echo "There was an error setting up your account.";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <title>TeaMe! - Now Signed Up</title>
</head>
<body>
  <div id="page-container">
    <div id="nav">
      <ul>
        <li><strong><a href="index.php" id="homelink">TeaMe!</a></strong></li>
        <li><a href="login.php">Log In</a></li>
        <li><a href="register.php">Sign Up</a></li>
        <li><a href="collection.php">My Collection</a></li>
        <li><a href="browse.php">Browse</a></li>
      </ul>
    </div>
    <div id="content-upper">
      <br><br>
      <div id="inner">
        <p>Signed up!</p>
      </div>
      <br><br>
    </div>
    <div id="content-lower">
      <br><br>
    </div>
    <div id="footer">
       <p>CS 290 Final Project by <a href="https://github.com/lblackwell">Lucia Blackwell</a></p>
    </div>
  </div>
</body>
</html>