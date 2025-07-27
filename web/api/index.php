<?php
require_once '../common/bootstrap.php';
$headers = apache_request_headers();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(403);
    echo "Ongeldig verzoek";
    return;
} elseif (
    !array_key_exists('User', $headers)
    || !array_key_exists('Pass', $headers)
    || !array_key_exists('Authentication', $headers)
) {
    http_response_code(401);
    echo "Please provide correct credentials.";
    return;
} else {
    $returnValue = validateApi($headers['User'], $headers['Pass'], $headers['Authentication']);
    echo $returnValue;
    // print_r($_POST);
}
// echo json_encode("Welcome to the API");
// echo "U dient inloggegevens te verstrekken";

function validateApi(string $user, string $pass, string $key)
{
    // $apiKey =
    //     SECRET_KEY
    $row = DB::queryFirstRow("SELECT * FROM users WHERE username = %s AND active = 1", $user);
    if (!$row) {
        http_response_code(401);
        return "U dient correcte inloggegevens te verstrekken";
    }

    if (!password_verify($pass, $row['password'])) {
        http_response_code(401);
        return "U dient correcte inloggegevens te verstrekken";
    }
    // var_dump(hash('sha512', $key . SECRET_KEY, false), $row['apiKey']);
    if (!$row['isBot']) {
        http_response_code(401);
        return "U dient correcte inloggegevens te verstrekken";
    }
    if (hash('sha512', $key . SECRET_KEY, false) != $row['apiKey']) {
        http_response_code(401);
        return "U dient correcte inloggegevens te verstrekken";
    }
    http_response_code(200);
    return "U bent ingelogd";
}
