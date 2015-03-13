<?php
    require '../common/bootstrap.php';
    require '../common/header.php';

    if (!isset($_GET['id'])) {
	   $session->redirect("images/overview");
    } else {
	    $id = $_GET['id'];
	    DB::query('SELECT * FROM images WHERE id = %d', $id);

        if (DB::count() == 0) {
            $session->("images/overview");
	    } else {
	        DB::update('images', [
               'owner' => $_SESSION['user']
	        ]), 'id = %d', $id);

            $session->redirect("images/single", "?id=$id");
	   }
    }
?>