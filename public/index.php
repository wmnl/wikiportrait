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

    <div class="oss-banner" id="ossBanner">
        <button class="oss-banner__close" id="ossBannerClose" aria-label="<?= t('common.close'); ?>">&times;</button>
        <svg class="oss-banner__icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
            fill="currentColor" aria-hidden="true">
            <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
        </svg>
        <span class="oss-banner__text"><?= t('oss.text'); ?></span>
    </div>

</div>
<?php
include 'common/footer.php';
?>