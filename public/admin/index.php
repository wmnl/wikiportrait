<?php
require_once '../common/bootstrap.php';

// Check for old v1 url's, and if so, redirect to old installation
if (!empty($_GET['id']) && !empty($_GET['secret']) && defined('V1_PATH')) {
    $url = sprintf(V1_PATH . '/admin/?id=%s&secret=%s', $_GET['id'], $_GET['secret']);
    header("Location:$url");
    exit;
}

if (!empty($_GET['logout'])) {
    $session->logout();
}

if (isset($_POST['postback'])) {
    isrequired('username', t('field.username'));
    isrequired('password', t('field.password'));

    if (!hasvalidationerrors()) {
        if ($session->login($_POST['username'], $_POST['password'])) {
            if (!empty($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit;
            } else {
                $session->redirect("/images/overview");
            }
        } else {
            addvalidationerror(t('login.wrong'));
        }
    }
}

if ($session->isLoggedIn()) {
    $session->redirect("/images/overview");
}

require_once '../common/header.php';
?>

<div id="content">
    <h2><?= t('login.title'); ?></h2>
    <p><?= t('login.intro'); ?></p>

    <?php
    if (hasvalidationerrors()) {
        showvalidationsummary();
    }
    ?>

    <form method="post">
        <div class="input-container">
            <label for="username"><i class="fa-solid fa-user fa-lg"></i><?= t('user.username'); ?></label>
            <input type="text" name="username" id="username" autocorrect="off" />
        </div>

        <div class="input-container">
            <label for="password"><i class="fa-solid fa-key fa-lg"></i><?= t('user.password'); ?></label>
            <input type="password" name="password" id="password" />
        </div>

        <div class="bottom right">
            <button class="green" type="submit" name="postback"><i
                    class="fa-solid fa-sign-in fa-lg"></i><?= t('login.button'); ?></button>
        </div>
    </form>
</div>

<?php
include '../common/footer.php';
?>