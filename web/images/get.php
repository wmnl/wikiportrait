<?php
    require '../common/bootstrap.php';

    if (!isset($_GET['id'])) {
	   $session->redirect("/images/overview");
    } else {
	    $id = $_GET['id'];
	    DB::query('SELECT * FROM images WHERE id = %d', $id);

        if (DB::count() == 0) {
            $session->redirect("/images/overview");
	    } else {
	        DB::update('images', [
               'owner' => $_SESSION['user']
	        ], 'id = %d', $id);

            $session->redirect("/images/single", "?id=$id");
	   }
    }

    require '../common/header.php';
?>