<?php
    include '../common/header.php';
    
    if (!isset($_GET['id']))
    {
	header("Location:overview.php");
    }
    
    else
    {
	$id = $_GET['id'];
	
	DB::query('SELECT * FROM images WHERE id = %d', $id);
	
	if (DB::count() == 0)
	{
	    header("Location:overview.php");
	}
	else
	{
	    DB::update('images', array(
		'owner' => $_SESSION['user']
	    ), 'id = %d', $id);
	    header("Location:single.php?id=$id");
	}
    }
?>