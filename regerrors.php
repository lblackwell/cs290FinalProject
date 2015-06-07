<?php

$input = $_REQUEST["input"];
$which = $_REQUEST["which"];
$error = "";

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

echo "<p>".$error."</p>";

?>