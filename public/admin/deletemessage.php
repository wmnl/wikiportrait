<?php

require_once '../common/bootstrap.php';
$session->checkAdmin();

if (isset($_POST['id'])) {
    DB::delete('messages', 'id = %d', $_POST['id']);
}

$session->redirect("/admin/messages");
