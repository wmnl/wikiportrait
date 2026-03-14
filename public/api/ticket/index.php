<?php

require_once '../../vendor/autoload.php';
$headers = apache_request_headers();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(403);
    echo "Ongeldig verzoek";
    return;
} elseif (!array_key_exists('User', $headers) || !array_key_exists('Authentication', $headers)) {
    http_response_code(401);
    echo "U dient correcte inloggegevens te verstrekken";
    return;
} elseif (!validateToken($headers)) {
    http_response_code(401);
    echo "U dient correcte inloggegevens te verstrekken";
    return;
}
if (isset($_POST['key'])) {
    $row = DB::queryFirstRow(
        "SELECT * from images WHERE `key`=%s",
        $_POST['key']
    );
    $returnData = [];
    $returnData['description'] = $row['description'] ?: $row['title'];
    $returnData['ticket'] = $row['ticket'] ?: 'VUL_HIER_HET_TICKETNUMMER_IN';
    $returnData['image'] = BASE_URL . "/uploads/images/" . $row['filename'];
    $returnData['date'] =    $row['date'] ?: null;
    $returnData['author'] = $row['source'];
    $returnData['filename'] = $row['filename'];
    $returnData['categories'] = $row['categories'];
    echo json_encode($returnData);
}

/**
 * @param array $headers
 *
 * @return bool
 */
function validateToken(array $headers)
{
    $token = [];
    preg_match('/^Bearer (.*)$/', $headers['Authentication'], $token);
    if (count($token) == 0) {
        return false;
    }
    $row = DB::queryFirstRow("SELECT token, id, expires, active FROM botkeys LEFT JOIN users ON botkeys.user=users.id" .
        " WHERE token=%s AND active=1 AND expires>=NOW();", $token[1]);
    if (count($row) == 0) {
        return false;
    }
    return true;
}
