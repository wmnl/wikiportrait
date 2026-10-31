<?php
require_once '../common/bootstrap.php';
require_once '../common/header.php';
$session->checkAdmin();
include 'tabs.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $session->redirect("/admin/users");
}

$row = DB::query('SELECT * FROM messages WHERE id = %d', $id);
$row = $row[0];

if (DB::count() == 0) {
    $session->redirect("/admin/messages");
}

if (isset($_POST['postback'])) {
    isrequired('title', t('field.title'));
    isrequired('message', t('field.message'));

    if (!hasvalidationerrors()) {
        DB::update('messages', [
            'title' => $_POST['title'],
            'message' => $_POST['message']
        ], 'id = %d', $_GET['id']);

        $session->redirect("/admin/messages");
    }
}
?>
<div id="content">
    <?php
    if (hasvalidationerrors()) {
        showvalidationsummary();
    }
    ?>

    <div class="page-header">
        <h2><?= t('message.edit_title'); ?></h2>
        <a href="messages.php" class="button red"><i
                class="fa-solid fa-times-circle fa-lg"></i><span><?= t('common.cancel'); ?></span></a>
    </div>

    <form method="post">
        <div class="input-container">
            <label for="title"><i class="fa-solid fa-tag fa-lg"></i><?= t('common.title'); ?></label>
            <input type="text" name="title" id="title" value="
            <?=
            htmlspecialchars($row['title']);
            ?>
            "
                required="required" />
        </div>

        <div class="input-container">
            <label for="message"><i class="fa-solid fa-align-left fa-lg"></i><?= t('common.message'); ?></label>
            <textarea required="required" name="message">
            <?=
            htmlspecialchars($row['message']);
            ?>
            </textarea>
        </div>

        <div class="bottom right">
            <button class="green" name="postback"><i class="fa-solid fa-floppy-o fa-lg"></i><?= t('common.save'); ?></button>
        </div>
    </form>
</div>
<?php
include '../common/footer.php';
?>