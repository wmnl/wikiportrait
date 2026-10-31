<?php

require_once '../common/bootstrap.php';
$session->checkLogin();

header('Content-Type: application/json');

$ids = $_POST['ids'] ?? [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'archive' || !is_array($ids) || !$ids) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ongeldig verzoek.']);
    exit;
}

DB::update('images', ['archived' => 1], 'id IN %li AND archived = 0', array_map('intval', $ids));

echo json_encode(['success' => true, 'message' => DB::affectedRows() . ' afbeeldingen gearchiveerd.']);
