<?php
require_once '../common/bootstrap.php';
$session->checkAdmin();

if (isset($_POST['postback'])) {
    isrequired('title', t('field.title'));
    isrequired('message', t('field.message'));

    if (!hasvalidationerrors()) {
        DB::insert('messages', [
            'title' => $_POST['title'],
            'message' => $_POST['message']
        ]);

        $session->redirect("/admin/messages");
    }
}

require_once '../common/header.php';
include 'tabs.php';
?>
<div id="content">

    <div class="page-header">

        <h2><?= t('message.add_title'); ?></h2>

        <a href="messages.php" class="button red"><i
                class="fa-solid fa-times-circle fa-lg"></i><span><?= t('common.cancel'); ?></span></a>

    </div>

    <?php
    if (hasvalidationerrors()) {
        showvalidationsummary();
    }
    ?>

    <form method="post">

        <div class="input-container">
            <label for="title"><i class="fa-solid fa-tag fa-lg"></i><?= t('common.title'); ?></label>
            <input type="text" name="title" id="title" value="
        <?php
        if (isset($_POST['title'])) {
            echo $_POST['title'];
        }
        ?>
        " required="required" />
        </div>

        <div class="input-container">
            <label for="message"><i class="fa-solid fa-align-left fa-lg"></i><?= t('common.message'); ?></label>
            <textarea required="required" name="message">
        <?php
        if (isset($_POST['message'])) {
            echo $_POST['message'];
        }
        ?>
        </textarea>
        </div>

        <div class="bottom right">
            <button class="green" type="submit" name="postback"><i class="fa-solid fa-plus fa-lg"></i><?= t('common.add'); ?></button>
        </div>

    </form>

</div>
<?php
include '../common/footer.php';
?>