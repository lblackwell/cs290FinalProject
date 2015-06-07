<?php

$zip = $_REQUEST["zip"];
$error = "";

if(strlen($zip) !== 5)
{
	$error = "Zip code must be five digits long.";
}

if(!is_numeric($zip))
{
	$error = "Zip code may only contain digits.";
}

echo "<p>".$error."</p>";

?>