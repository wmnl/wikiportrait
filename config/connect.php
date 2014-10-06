<?php
	include 'config.php';
	$connection = mysqli_connect($DBserver, $DBuser, $DBpassword, $DBname) or die("Some error occurred during connection " . mysqli_error($connection));
?>
