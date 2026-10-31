<?php

//@phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class Session
{
    public function __construct()
    {

        session_status() != PHP_SESSION_ACTIVE ? session_start() : '';
        $cookieLifetime = 365 * 24 * 60 * 60;
        setcookie(session_name(), session_id(), time() + $cookieLifetime);
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
        $row = DB::queryFirstRow("SELECT * FROM users WHERE username = %s AND active = 1", $_POST['username']);
        if (!$row) {
            return false;
        }
        if (!password_verify($_POST['password'], $row['password'])) {
            return false;
        }
        if ($row['isBot'] == 1) {
            return false;
        }
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
