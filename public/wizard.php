<?php
require_once 'common/bootstrap.php';
require_once 'common/header.php';
require_once 'question.php';

if (!isset($_GET['question'])) {
    $question = "first";
} else {
    $question = $_GET['question'];
}
?>
<div id="content">
    <h2><?= t('wizard.title'); ?></h2>
    <?php
    switch ($question) {
        case "noupload":
            result("noupload");
            break;

        case "upload":
            result("upload");
            break;

        case "success":
            result("success");
            break;

        case "verificatie":
            result("verificatie");
            break;

        case "failupload":
            result("failupload");
            break;

        case "duplicate":
            result("duplicate");
            break;

        case "lowres":
            result("lowres");
            break;

        default: // including "first":
            showquestion(t('wizard.q.copyright'), "noupload", "ownwork", t('wizard.q.copyright_help'));
            break;

        case "ownwork":
            showquestion(t('wizard.q.ownwork'), "employment", "noupload");
            break;

        case "employment":
            showquestion(
                t('wizard.q.employment'),
                "employpermission",
                "ownpermission",
                t('wizard.q.employment_help')
            );
            break;

        case "employpermission":
            showquestion(
                t('wizard.q.employpermission'),
                "upload",
                "noupload",
                t('wizard.q.employpermission_help')
            );
            break;

        case "ownpermission":
            showquestion(t('wizard.q.ownpermission'), "upload", "noupload", t('wizard.q.ownpermission_help'));
            break;
    }
    ?>
</div>
<?php
include 'common/footer.php';
?>