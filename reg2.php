<?php

include "secret.php";

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $db_username, $db_pw, $db_username);

if(!$mysqli || $mysqli->connect_errno)
{
  echo "There was a problem connecting to the database.";
}

$username = mysqli_real_escape_string($mysqli, $_REQUEST["username"]);
$email = mysqli_real_escape_string($mysqli, $_REQUEST["email"]);
$password = mysqli_real_escape_string($mysqli, $_REQUEST["password"]);
$error = "No error.";

if($which == "username" && strlen($input) > 45)
{
	$error = "Username must be 45 characters or fewer.";
}

if($which == "username" && strlen($input) < 3)
{
	$error = "Username must be at least 3 characters.";
}

if($which == "email" && strlen($input) > 100)
{
	$error = "Email address must be 100 characters or fewer.";
}

if($which == "email" && strlen($input) < 5)
{
	$error = "Email address must be at least 5 characters.";
}

if($which == "password" && strlen($input) > 45)
{
	$error = "Password must be 100 characters or fewer.";
}

if($which == "password" && strlen($input) < 3)
{
	$error = "Password must be at least 3 characters.";
}

$checkUsername = mysqli_query($mysqli, "SELECT * FROM user WHERE username='".$username."'");
if(mysqli_num_rows($checkUsername) == 1)
{
	$error = "That name is already is use. Please choose a different username or <a href='login.php'>log in</a>.";
}

$checkEmail = mysqli_query($mysqli, "SELECT * FROM user WHERE email='".$email."'");
if(mysqli_num_rows($checkEmail) == 1)
{
	$error = "That email address is already in use. Please use a different email address or <a href='login.php'>log in</a>.";
}

echo "<p>".$error."</p>";

?>