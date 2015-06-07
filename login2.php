<?php

include "secret.php";

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $db_username, $db_pw, $db_username);

if(!$mysqli || $mysqli->connect_errno)
{
  echo "There was a problem connecting to the database.";
}

$username = mysqli_real_escape_string($mysqli, $_REQUEST["username"]);
$password = mysqli_real_escape_string($mysqli, $_REQUEST["password"]);
$error = "";

if(strlen($username) < 3)
{
	$error = "Username must contain at least three characters.";
}
else
{
	$checkUsername = mysqli_query($mysqli, "SELECT * FROM user WHERE username='".$username."'");

	if(mysqli_num_rows($checkUsername) !== 1)
	{
		$error = "Username not found.";
	}
}

if(strlen($password) < 3)
{
	$error = "Invalid password.";
}
else
{
	$checkUserPassCombo = mysqli_query($mysqli, "SELECT * FROM user WHERE username='".$username."' AND password='".sha1($password)."'");

	if(mysqli_num_rows($checkUserPassCombo) !== 1)
	{
		$error = "Username and password did not match.";
	}
}

if(strlen($error) == 0)
{
	$error = "No error.";
}

echo "<p>".$error."</p>";

?>