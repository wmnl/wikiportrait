<?php
    include '../header.php';
    
    if (!isset($_GET['id']))
    {
	header("Location:overview.php");
    }
    
    else
    {
	$id = mysqli_real_escape_string($connection, $_GET['id']);
	
	$query = sprintf("SELECT * FROM images WHERE id = %d", $id);
	$result = mysqli_query($connection, $query);
	if (mysqli_num_rows($result) == 0)
	{
	    header("Location:overview.php");
	}
	else
	{
	    $query = sprintf("UPDATE images SET owner = %d WHERE id = %d", $_SESSION['user'], $id);
	    mysqli_query($connection, $query);
	    header("Location:single.php?id=$id");
	}
    }
?>