<?php
    include '../common/header.php';
    include 'tabs.php';
    checkAdmin();
    
    if (isset($_GET['id']))
    {
	
	DB::query("SELECT * FROM messages WHERE id = %d", $_GET['id']);
	
	if (DB::count() != 0)
	{
	    DB::delete('messages', 'id = %d', $_GET['id']);
	}
	else 
	{
	    header("Locaion: messages.php");
	}
    }
?>