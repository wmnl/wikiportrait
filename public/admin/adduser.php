<?php
require_once '../common/bootstrap.php';
$session->checkAdmin();

if (isset($_POST['postback'])) {
    $admin = isset($_POST['admin']);

    isrequired('username', t('field.username'));
    isrequired('otrsname', t('field.vrts_name'));
    isrequired('password', t('field.password'));
    checkusername($_POST['username']);
    comparepassword($_POST['password'], $_POST['password2']);
    validateEmail('email');

    if (!hasvalidationerrors()) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        DB::insert('users', [
            'username' => $_POST['username'],
            'password' => $password,
            'otrsname' => $_POST['otrsname'],
            'email' => $_POST['email'],
            'isSysop' => $admin,
            'isBot' =>  $_POST['bot'] ?? false,
            'apiKey' => $_POST['bot'] ? hash('sha512', $_POST['key'] . SECRET_KEY, false) : '',
            'active' => 1
        ]);

        $session->redirect("/admin/users");
    }
}
require_once '../common/header.php';
include 'tabs.php';

?>
<div id="content">

    <div class="page-header">
        <h2><?= t('user.add_title'); ?></h2>
        <a href="users.php" class="button red"><i class="fa-solid fa-times-circle fa-lg"></i><span><?= t('common.cancel'); ?></span></a>
    </div>

    <?php
    if (hasvalidationerrors()) {
        showvalidationsummary();
    }
    ?>

    <form method="post">
        <?= csrfField(); ?>
        <div class="input-container">
            <label for="username"><i class="fa-solid fa-user fa-lg"></i><?= t('user.username'); ?></label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>" />
        </div>

        <div class="input-container">
            <label for="otrsname"><i class="fa-solid fa-briefcase fa-lg"></i><?= t('user.vrts_name'); ?></label>
            <input type="text" name="otrsname" id="otrsname" value="<?= htmlspecialchars($_POST['otrsname'] ?? ''); ?>" />
        </div>

        <div class="input-container">
            <label for="password"><i class="fa-solid fa-key fa-lg"></i><?= t('user.password'); ?></label>
            <input type="password" name="password" id="password" />
        </div>

        <div class="input-container">
            <label for="password2"><i class="fa-solid fa-key fa-lg"></i><?= t('user.password_again'); ?></label>
            <input type="password" name="password2" id="password2" />
        </div>

        <div class="input-container">
            <label for="email"><i class="fa-solid fa-envelope fa-lg"></i><?= t('user.email'); ?></label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>" />
        </div>

        <div class="input-container">
            <label for="admin"><i class="fa-solid fa-user-md fa-lg"></i><?= t('user.admin'); ?></label>
            <div class="checkbox">
                <input type="checkbox" name="admin" id="admin" /><label for="admin"><?= t('common.yes'); ?></label>
            </div>
        </div>
        <div class="input-container">
            <label for="admin"><i class="fa-solid fa-user-md fa-lg"></i><?= t('user.bot'); ?></label>
            <div class="checkbox">
                <input type="checkbox" name="bot" id="bot" value="1" /><label for="bot"><?= t('common.yes'); ?></label>
            </div>
        </div>
        <div class="input-container d-none">
            <label for="key"><i class="fa-solid fa-hashtag fa-lg"></i><?= t('user.api_key'); ?></label>
            <input type="text" name="key" id="key" />
        </div>

        <div class="bottom right">
            <button class="green" type="submit" name="postback"><i class="fa-solid fa-plus fa-lg"></i><?= t('common.add'); ?></button>
        </div>
    </form>
</div>
<?php
include '../common/footer.php';
?>
