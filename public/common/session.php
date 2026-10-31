<?php

//@phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class Session
{
    private const COOKIE_LIFETIME = 30 * 24 * 60 * 60;

    // Older versions set the session cookie without a path, so browsers may still have copies for these directories
    private const LEGACY_COOKIE_DIRS = ['admin', 'images', 'ml', 'api/login', 'api/ticket'];

    public function __construct()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            $cookieOptions = [
                'path'     => ini_get('session.cookie_path') ?: '/',
                'secure'   => ($_SERVER['HTTPS'] ?? '') === 'on',
                'httponly' => true,
                'samesite' => 'Lax',
            ];
            session_set_cookie_params(['lifetime' => self::COOKIE_LIFETIME] + $cookieOptions);
            $hasLegacyCookies = $this->useRootCookie();
            session_start();
            // Only after session_start(), which drops any session Set-Cookie header sent before it
            if ($hasLegacyCookies) {
                $this->removeLegacyCookies($cookieOptions);
            }

            // PHP only sends the cookie for new sessions; renew the expiry for returning users
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), session_id(), ['expires' => time() + self::COOKIE_LIFETIME] + $cookieOptions);
            }
        }
    }

    /**
     * A legacy cookie for e.g. /images is sent before the one for / and PHP uses the first, so the
     * page would see an old session. Use the last one instead: browsers send the cookie for / last.
     * Returns whether legacy cookies were sent.
     */
    private function useRootCookie(): bool
    {
        $pattern = '/(?:^|;)\s*' . preg_quote(session_name(), '/') . '=([^;]*)/';
        preg_match_all($pattern, $_SERVER['HTTP_COOKIE'] ?? '', $m);
        if (count($m[1]) < 2) {
            return false;
        }

        $id = end($m[1]);
        if (preg_match('/^[A-Za-z0-9,-]{1,128}$/', $id)) {
            session_id($id);
            $_COOKIE[session_name()] = $id;
        }
        return true;
    }

    private function removeLegacyCookies(array $cookieOptions)
    {
        $basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
        foreach (self::LEGACY_COOKIE_DIRS as $dir) {
            setcookie(session_name(), '', ['expires' => 1, 'path' => "$basePath/$dir"] + $cookieOptions);
        }
    }

    public function getLastUploadKey()
    {
        return $_SESSION['lastuploadkey'];
    }

    public function setLastUploadKey($key)
    {
        $_SESSION['lastuploadkey'] = $key;
    }

    public function getLastUploadEmail()
    {
        return array_key_exists('email', $_SESSION) ? $_SESSION['email'] : null;
    }

    public function setLastUploadEmail($email)
    {
        $_SESSION['email'] = $email;
    }

    public function redirect($page, $args = false)
    {
        global $basispad;
        $location = "Location:$basispad$page.php";
        if ($args) {
            $location .= $args;
        }
        header($location);
        exit;
    }

    public function csrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function checkCsrf()
    {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!is_string($token) || !hash_equals($this->csrfToken(), $token)) {
            http_response_code(403);
            exit(t('error.csrf'));
        }
    }

    public function isLoggedIn()
    {
        return !empty($_SESSION['user']);
    }

    public function isSysop()
    {
        return !empty($_SESSION['isSysop']);
    }

    public function checkLogin()
    {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect("/admin/index");
            exit;
        }
    }

    public function checkAdmin()
    {
        $this->checkLogin();

        if (!$this->isSysop()) {
            exit(t('error.admin_only'));
        }
    }

    public function getUserName()
    {
        return $_SESSION['username'];
    }

    public function login($user, $pass)
    {
        $row = DB::queryFirstRow("SELECT * FROM users WHERE username = %s AND active = 1", $user);
        if (!$row) {
            return false;
        }
        if (!password_verify($pass, $row['password'])) {
            return false;
        }
        if ($row['isBot'] == 1) {
            return false;
        }
        // New session id after login, against session fixation
        session_regenerate_id(true);
        $_SESSION['user']     = $row['id'];
        $_SESSION['isSysop']  = $row['isSysop'] == 1;
        $_SESSION['username'] = $row['username'];

        return true; // redirect-logica NIET hier, maar in de loginpagina zelf
    }
    public function logout()
    {
        session_destroy();
        $this->redirect("/admin/index");
    }
}
