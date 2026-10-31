<?php
//@phpcs:disable Generic.Files.LineLength.TooLong
function showquestion($text, $yes, $no, $exp = null)
{
    $yesLabel = t('common.yes');
    $noLabel = t('common.no');
    echo "<p>$text</p>";
    if (!empty($exp)) {
        echo "<div class=\"box grey\">$exp</div>";
    }
    $rnd_num = intval(time() * (mt_rand() / mt_rand()));
    switch ($rnd_num % 2) {
        case 0:
            echo "<div class=\"bottom center\"><a class=\"button\" href=\"wizard.php?question=$no\">$noLabel</a><span class=\"divider\">&nbsp;</span><a class=\"button\" href=\"wizard.php?question=$yes\">$yesLabel</a></div>";
            break;
        case 1:
            echo "<div class=\"bottom center\"><a class=\"button\" href=\"wizard.php?question=$yes\">$yesLabel</a><span class=\"divider\">&nbsp;</span><a class=\"button\" href=\"wizard.php?question=$no\">$noLabel</a></div>";
            break;
    }
}

function result($result)
{
    global $session;

    if ($result == "noupload") {
        echo "<h3>" . t('wizard.advice') . "</h3>";
        echo "<div class=\"box red\">" . t('wizard.no_upload') . "</div>";
        echo "<div class=\"bottom right\"><a class=\"button\" href=\"index.php\"><i class=\"fa-solid fa-home fa-lg\"></i>" . t('common.back_home') . "</a></div>";
    } elseif ($result == "success") {
        $key = $session->getLastUploadKey();
        echo "<h3>" . t('wizard.upload_done') . "</h3>";
        echo "<div class=\"box green\">" . t('upload.success_text', ['track_url' => "track.php?key=$key"]) . "</div>";
        echo "<div class=\"bottom right\"><a class=\"button\" href=\"upload.php\"><i class=\"fa-solid fa-cloud-upload fa-lg\"></i>" . t('common.upload_another') . "</a></div>";
    } elseif ($result == "verificatie") {
        $email = $session->getLastUploadEmail();
        echo "<h3>" . t('wizard.verification_required') . "</h3>";
        echo "<div class=\"box blue\">" . t('wizard.verification_sent', ['email' => $email]) . "</div>";
    } elseif ($result == "duplicate") {
        echo "<h3>" . t('wizard.upload_failed') . "</h3>";
        echo "<div class=\"box red\">" . t('error.duplicate') . "<br /></div>";
        echo "<div class=\"bottom right\"><a class=\"button\" href=\"index.php\"><i class=\"fa-solid fa-home fa-lg\"></i>" . t('common.back_home') . "</a></div>";
    } elseif ($result == "lowres") {
        $email = htmlspecialchars($session->getLastUploadEmail());
        echo "<h3>" . t('wizard.low_resolution') . "</h3>";
        echo "<div class=\"box red\">" . t('wizard.low_resolution_text', ['email' => $email]) . "</div>";
        echo "<div class=\"bottom right\"><a class=\"button\" href=\"upload.php\"><i class=\"fa-solid fa-cloud-upload fa-lg\"></i>" . t('wizard.upload_other') . "</a></div>";
    } elseif ($result == "failupload") {
        ?>
        <h3><?= t('wizard.upload_failed'); ?></h3>
        <div class="box red"><?= t('wizard.upload_failed_text'); ?></div>
        <?php
    } else {
        echo "<h3>" . t('wizard.advice') . "</h3>";
        echo "<div class=\"box green\">" . t('wizard.upload_license') . "</div>";
        echo "<div class=\"bottom right\"><a class=\"button\" href=\"upload.php\"><i class=\"fa-solid fa-cloud-upload fa-lg\"></i>" . t('wizard.upload_button') . "</a></div>";
    }
}
?>
