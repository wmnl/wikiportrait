<?php

// Bots authenticate with headers, not a browser session
define('CSRF_EXEMPT', true);
require_once '../../vendor/autoload.php';

$headers = apache_request_headers();

if (defined('API_DEBUG') && API_DEBUG) {
    var_dump($headers);
}

// Punt 7: Alleen POST toestaan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo t('api.method_not_allowed');
    return;
}

if (
    !array_key_exists('User', $headers)
    || !array_key_exists('Pass', $headers)
    || !array_key_exists('Authentication', $headers)
) {
    http_response_code(401);
    // Punt 9: Generieke foutmelding naar buiten
    echo t('api.invalid_credentials');
    return;
}

$returnValue = validateApi($headers['User'], $headers['Pass'], $headers['Authentication']);
echo $returnValue;
return;

/**
 * @param string $user
 * @param string $pass
 * @param string $key
 *
 * @return string
 */
function validateApi(string $user, string $pass, string $key): string
{
    /** @var array|null $row */
    $row = DB::queryFirstRow("SELECT * FROM users WHERE username = %s AND active = 1", $user);

    // Punt 9: Alle 401-responses geven dezelfde melding terug
    if (!$row) {
        http_response_code(401);
        return t('api.invalid_credentials');
    }

    if (!$row['isBot'] || !$row['active']) {
        http_response_code(401);
        return t('api.invalid_credentials');
    }

    if (!password_verify($pass, $row['password'])) {
        http_response_code(401);
        return t('api.invalid_credentials');
    }

    if (defined('API_DEBUG') && API_DEBUG) {
        var_dump($key, hash('sha512', $key . SECRET_KEY, false), $row['apiKey']);
    }

    // Punt 8: hash_equals voorkomt timing attacks
    if (!hash_equals(hash('sha512', $key . SECRET_KEY, false), $row['apiKey'])) {
        http_response_code(401);
        return t('api.invalid_credentials');
    }

    $hashkey = hash('sha512', $key . SECRET_KEY . date('c'), false);
    $expiry = new DateTime();
    $expiry->modify('+1 hour');

    DB::insert('botkeys', [
        'token'   => $hashkey,
        'user'    => $row['id'],
        'expires' => $expiry->format('Y-m-d H:i:s'),
    ]);

    http_response_code(200);
    return $hashkey;
}
