<?php
require_once 'common/bootstrap.php';
require_once 'common/header.php';
//@phpcs:disable Generic.Files.LineLength.TooLong
?>
<div id="content">
    <?php
    if (!CLOSED) {
        ?>
        <h2><?= t('index.welcome'); ?></h2>
        <p><?= t('index.intro1'); ?></p>
        <p><?= t('index.intro2'); ?></p>

        <div class="bottom center">
            <a href="wizard.php" class="button green bigbutton"><i class="fa-solid fa-cloud-upload fa-lg"></i><?=
                t('index.submit_button'); ?></a>
        </div>
    <?php } else { ?>
        <h2><?= t('index.welcome'); ?></h2>
        <p><?= t('closed.text'); ?></p>
        <p><?= t('closed.apology'); ?></p>
    <?php } ?>
</div>
<?php
include 'common/footer.php';
?>
