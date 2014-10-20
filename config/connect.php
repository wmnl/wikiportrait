<?php
	include 'config.php';
	$connection = mysqli_connect($DBserver, $DBuser, $DBpassword, $DBname);
	if (!$connection)
	{
	echo "An error occurred during connection: " . mysqli_connect_error($connection);
	}
?>
