<?php
require_once '../common/bootstrap.php';
$session->checkLogin();
require_once '../common/header.php';

?>
<div id="content">
    <h2><?= t('canned.title'); ?></h2>

    <div class="input-container">
        <label for="message"><i class="fa-solid fa-align-left fa-lg"></i><?= t('common.message'); ?></label>
        <?php
        $row = null;
        if (isset($_GET['image']) && isset($_GET['message'])) {
            $row = DB::queryFirstRow(
                'SELECT * FROM messages LEFT JOIN users ON users.id = %d LEFT JOIN images '
                    . 'ON images.id = %d WHERE messages.id = %d',
                $_SESSION['user'],
                $_GET['image'],
                $_GET['message']
            );
        }

        if (!$row) {
            echo "<div class=\"box red\">" . t('canned.not_found') . "</div>";
        } else {
            $templates = ["%%name%%", "%%title%%", "%%otrsname%%"];
            $replace = [$row['name'], $row['title'], $row['otrsname']];
            $newTxt = str_replace($templates, $replace, $row['message']);
            ?>
            <textarea name="message" onclick="this.focus();this.select()"><?= htmlspecialchars($newTxt); ?></textarea>
            <?php
        }

        ?>
    </div>
</div>
<?php
include '../common/footer.php';
