<?php
require_once '../common/bootstrap.php';
$session->checkLogin();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Logged-in users may only edit themselves
    if (!$session->isSysop() && $id != $_SESSION['user']) {
        $session->redirect("/admin/index");
    }
} else {
    $session->redirect("/admin/users");
}

$row = DB::queryFirstRow('SELECT * FROM users WHERE id = %d', $_GET['id']);

if (DB::count() == 0) {
    echo t('user.not_found');
} else {
    if (isset($_POST['postback'])) {
        $admin = isset($_POST['admin']);
        $active = isset($_POST['active']);

        // Make sure non-admins don't promote themselves to admin
        if ($admin && !$session->isSysop()) {
            die();
        }

        isrequired('username', t('field.username'));
        isrequired('otrsname', t('field.vrts_name'));
        comparepassword($_POST['password'], $_POST['password2']);
        validateEmail('email');

        if (!hasvalidationerrors()) {
            $data = [
                'username' => $_POST['username'],
                'otrsname' => $_POST['otrsname'],
                'email' => $_POST['email'],
                'isSysop' => $admin,
                'isBot' => $_POST['bot'] ?? false,
                'active' => $active,
            ];
            if ($_POST['bot'] && $_POST['key']) {
                $data['apiKey'] = hash('sha512', $_POST['key'] . SECRET_KEY, false);
            }
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }
            DB::update('users', $data, 'id = %d', $_GET['id']);

            $session->redirect($session->isSysop() ? "/admin/users" : "/images/overview");
        }

        if (hasvalidationerrors()) {
            showvalidationsummary();
        }
    }
}
require_once '../common/header.php';
include 'tabs.php';
?>
<div id="content">
    <div class="page-header">
        <h2><?= t('user.edit_title'); ?></h2>
        <a href="users.php" class="button red"><i class="fa-solid fa-times-circle fa-lg"></i><span><?= t('common.cancel'); ?></span></a>
    </div>


    <form method="post">
        <?= csrfField(); ?>

        <?php
        if ($session->isSysop()) {
            $inputClass = "";
        } else {
            $inputClass = 'style="display:none;"';
        }
        ?>

        <div class="input-container" <?= $inputClass; ?>>
            <label for="username"><i class="fa-solid fa-user fa-lg"></i><?= t('user.username'); ?></label>
            <input type="text" name="username" id="username"
                value="<?php echo htmlspecialchars($row['username']); ?>" />
        </div>

        <div class="input-container">
            <label for="otrsname"><i class="fa-solid fa-briefcase fa-lg"></i><?= t('user.vrts_name'); ?></label>
            <input type="text" name="otrsname" id="otrsname"
                value="<?php echo htmlspecialchars($row['otrsname']); ?>" />
        </div>

        <div class="input-container">
            <label for="password"><i class="fa-solid fa-key fa-lg"></i><?= t('user.password'); ?></label>
            <input type="password" name="password" id="password"
                placeholder="<?= t('user.password_placeholder'); ?>" />
        </div>

        <div class="input-container">
            <label for="password2"><i class="fa-solid fa-key fa-lg"></i><?= t('user.password_again'); ?></label>
            <input type="password" name="password2" id="password2"
                placeholder="<?= t('user.password_placeholder'); ?>" />
        </div>

        <div class="input-container">
            <label for="email"><i class="fa-solid fa-envelope fa-lg"></i><?= t('user.email'); ?></label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($row['email']); ?>" />
        </div>

        <div class="input-container" <?= $inputClass; ?>>
            <label for="admin"><i class="fa-solid fa-user-md fa-lg"></i><?= t('user.admin'); ?></label>
            <div class="checkbox">
                <input type="checkbox" name="admin" id="admin" 
                <?php
                if ($row['isSysop'] == 1) {
                    echo "checked";
                }
                ?>
                                                                /><label for="admin"><?= t('common.yes'); ?></label>
            </div>
        </div>

        <div class="input-container" <?= $inputClass; ?>>
            <label for="active"><i class="fa-solid fa-power-off fa-lg"></i><?= t('user.activated'); ?></label>
            <div class="checkbox">
                <input type="checkbox" name="active" id="active" 
                <?php
                if ($row['active'] == 1) {
                    echo "checked";
                }
                ?>
                                                                    /><label for="active"><?= t('common.yes'); ?></label>
            </div>
        </div>
        <div class="input-container">
            <label for="admin"><i class="fa-solid fa-user-md fa-lg"></i><?= t('user.bot'); ?></label>
            <div class="checkbox">
                <input type="checkbox" name="bot" id="bot" value="1" 
                <?php
                if ($row['isBot'] == 1) {
                    echo "checked";
                }
                ?>
                                                                        /><label for="bot"><?= t('common.yes'); ?></label>
            </div>
        </div>
        <div class="input-container 
        <?php
        if ($row['isBot'] != 1) {
                                        echo 'd-none';
        }
        ?>
                                    ">
            <label for="key"><i class="fa-solid fa-hashtag fa-lg"></i><?= t('user.api_key'); ?></label>
            <input type="text" name="key" id="key" />
        </div>
        <div class="bottom right">
            <button class="green" name="postback"><i class="fa-solid fa-floppy-o fa-lg"></i><?= t('common.save'); ?></button>
        </div>

    </form>
</div>
<?php
include '../common/footer.php';
?>
