<?php

require_once '../common/bootstrap.php';
$session->checkLogin();

$id = (int) ($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !DB::queryFirstRow('SELECT id FROM images WHERE id = %d', $id)) {
    $session->redirect("/images/overview");
}

DB::update('images', [
    'owner' => $_SESSION['user']
], 'id = %d', $id);

$session->redirect("/images/single", "?id=$id");
