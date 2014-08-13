<?php
    include '../header.php';
    include 'tabs.php';
    checkAdmin();
    
    if (isset($_GET['id']))
    {
	$id = mysql_real_escape_string($_GET['id']);
	
	$query = sprintf("SELECT * FROM messages WHERE id = %d", $id);
	$result = mysql_query($query);
	
	if (mysql_num_rows($result) == 1)
	{
	    mysql_query(sprintf("DELETE FROM messages WHERE id = %d", mysql_real_escape_string($id)));
	    header("Location: messages.php");
	}
    }
?>