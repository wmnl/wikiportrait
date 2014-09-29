<?php
    include '../header.php';
    include 'tabs.php';
    checkAdmin();
    
    if (isset($_GET['id']))
    {
	$id = mysqli_real_escape_string($connection, $_GET['id']);
	
	$query = sprintf("SELECT * FROM messages WHERE id = %d", $id);
	$result = mysqli_query($connection, $query);
	
	if (mysqli_num_rows($result) == 1)
	{
	    mysqli_query($connection, sprintf("DELETE FROM messages WHERE id = %d", mysqli_real_escape_string($connection, $id)));
	    header("Location: messages.php");
	}
    }
?>