<?php
class Session {
    function __construct() {
        session_start();
        $cookieLifetime = 365 * 24 * 60 * 60;
        setcookie(session_name(),session_id(),time()+$cookieLifetime);
    }

    public function getLastUploadKey() {
        return $_SESSION['lastuploadkey'];
    }

    public function setLastUploadKey($key) {
        $_SESSION['lastuploadkey'] = $key;
    }

    public function redirect($page, $args = false) {
        global $basispad;
        $location = "Location:$basispad$page.php";
        if ($args) $location .= $args;
        header($location);
    }

    public function isLoggedIn() {
        return !empty($_SESSION['user']);
    }

    public function isSysop() {
        return !empty($_SESSION['isSysop']);
    }

    public function checkLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect("/admin");
        }
    }

    public function checkAdmin() {
        $this->checkLogin();

        if (!$this->isSysop()) {
            $this->redirect("/index");
        }
    }

    public function getUserName() {
        return $_SESSION['username'];
    }

    public function login($user, $pass) {
        $row = DB::queryFirstRow("SELECT * FROM users WHERE username = %s AND active = 1", $_POST['username']);

        if (!$row) {
            return false;
        }


        if (!password_verify($_POST['password'], $row['password'])) {
            return false;
        }

        $_SESSION['user'] = $row['id'];
        $_SESSION['isSysop'] = $row['isSysop'] == 1;
        $_SESSION['username'] = $row['username'];

        return true;
    }

    public function logout() {
        session_destroy();
        $this->redirect("/admin");
    }
}