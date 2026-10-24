<?php
//@phpcs:disable Generic.Files.LineLength.TooLong

require_once 'common/bootstrap.php';

if (($_GET['upload_error'] ?? '') === 'te_groot') {
    $maxSize = ini_get('post_max_size');
    addvalidationerror(t('validation.file_too_large', ['max' => $maxSize]));
}

if (isset($_POST['postback'])) {
    checkUpload();
}
require_once 'common/header.php';
if (!CLOSED) {
    ?>
<script>
function comparenames() {
    var depicted = document.getElementById('title').value;
    var photographer = document.getElementById('source').value;

    if (depicted.length > 0 && photographer.length > 0 &&
        (depicted.toLowerCase() == photographer.toLowerCase())) {
        swal(<?= json_encode(t('upload.same_person_title'), JSON_HEX_TAG); ?>,
            <?= json_encode(t('upload.same_person_text'), JSON_HEX_TAG); ?>,
            'warning');
    }
}
$(document).ready(function() {
    $(document).on('change', 'input[name="source_self"]', function() {
        if ("No" === $(this).val()) {
            $('#source_container').removeClass('hidden');
        } else {
            $('#source').removeAttr('required');
            $('#source_container').addClass('hidden');
        }
    });
})
</script>
<div id="content">
    <?php
    if (hasvalidationerrors()) {
        showvalidationsummary();
    }
    ?>

    <h2><?= t('upload.title'); ?></h2>

    <form method="post" enctype="multipart/form-data">

        <div class="input-container">
            <label for="file"><i aria-hidden="true" class="fa-solid fa-file-image-o fa-lg"></i><?= t('upload.choose_file'); ?></label>
            <div class="file">
                <input type="file" name="file" id="file" required="required" accept="image/*" />
            </div>
        </div>

        <div class="input-container">
            <label for="title"><i aria-hidden="true" class="fa-solid fa-eye fa-lg"></i><?= t('upload.depicted'); ?></label>
            <input type="text" name="title" id="title" required="required"
                value="<?= (!empty($_POST['title'])) ? $_POST['title'] : ''; ?>" onblur="comparenames()" />
        </div>

        <div class="input-container">
            <label for="source"><i aria-hidden="true" class="fa-solid fa-camera fa-lg"></i><?= t('upload.own_photo'); ?></label>
            <div class="radiogroup">
                <input type="radio" name="source_self" value="Yes" id="source_self_yes" required="required"
                    <?= (array_key_exists('source_self', $_POST) && $_POST['source_self'] == 'Yes') ? 'checked="checked"' : ''; ?>><?= t('upload.own_yes'); ?><br>
                <input type="radio" name="source_self" value="No" id="source_self_no" required="required" 
                <?php
                echo (array_key_exists('source_self', $_POST) && $_POST['source_self'] == 'No') ? 'checked="checked"' :
                                                                                                                    '';
                ?>
                ><?= t('upload.own_no'); ?><br>
                <input type="radio" name="source_self" value="Unknown" id="source_self_unknown" required="required" 
                <?php
                    //phpcs:ignore Squiz.PHP.EmbeddedPhp.Indent
                                                                                                                        echo (array_key_exists('source_self', $_POST) && $_POST['source_self'] == 'Unknown') ?
                                                                                                                            'checked="checked"' : '';
                ?>
                ><?= t('upload.own_unknown'); ?>
            </div>
        </div>

        <div class="input-container hidden" id="source_container">
            <label for="source"><i aria-hidden="true" class="fa-solid fa-camera-retro fa-lg"></i><?= t('upload.photographer'); ?></label>
            <input type="text" name="source" id="source" 
            <?php
            echo (array_key_exists('source_self', $_POST) &&
                                                                    $_POST['source_self'] != 'Yes') ? 'required="required"' : '';
            ?>
            value="<?= (!empty($_POST['source'])) ? $_POST['source'] : ''; ?>" onblur="comparenames()" />
        </div>

        <div class="input-container">
            <label for="name"><i aria-hidden="true" class="fa-solid fa-user fa-lg"></i><?= t('upload.your_name'); ?></label>
            <input type="text" name="name" id="name" required="required"
                value="<?= (!empty($_POST['name'])) ? $_POST['name'] : ''; ?>" />
        </div>

        <div class="input-container">
            <label for="email"><i aria-hidden="true" class="fa-solid fa-envelope fa-lg"></i><?= t('upload.your_email'); ?></label>
            <input type="email" id="email" name="email" required="required"
                value="<?= (!empty($_POST['email'])) ? $_POST['email'] : ''; ?>" />
        </div>

        <div class="input-container">
            <label for="date"><i aria-hidden="true" class="fa-solid fa-calendar fa-lg"></i><?= t('upload.date'); ?>
                <span class="optional"><?= t('upload.optional'); ?></span></label>
            <input type="text" name="date" id="date" value="<?= (!empty($_POST['date'])) ? $_POST['date'] : ''; ?>" />
        </div>

        <div class="input-container">
            <label for="description"><i aria-hidden="true" class="fa-solid fa-comment fa-lg"></i><?= t('upload.description'); ?> <span
                    class="optional"><?= t('upload.optional'); ?></span></label>
            <textarea name="description"
                id="description"><?= (!empty($_POST['description'])) ? $_POST['description'] : ''; ?></textarea>
        </div>

        <div class="input-container">
            <label><i aria-hidden="true" class="fa-solid fa-bars fa-lg"></i> <?= t('upload.license_terms'); ?></label>
            <textarea disabled="disabled"><?= t('upload.license_text'); ?></textarea>
        </div>
        <?php //phpcs:disable Generic.Files.LineLength.TooLong  
        ?>
        <div class="input-container">
            <label for="terms"><i aria-hidden="true" class="fa-solid fa-gavel fa-lg"></i><?= t('upload.consent'); ?></label>
            <div class="checkbox">
                <input type="checkbox" name="terms" id="terms" /><label for="terms"><?=
                    t('upload.consent_terms', ['privacy_url' => $basispad . '/privacyverklaring.php']); ?></label><br />
                <input type="checkbox" name="euvs" id="euvs" /><label for="euvs"><?=
                    t('upload.consent_storage'); ?></label>
            </div>
        </div>
        <?php //phpcs:enable Generic.Files.LineLength.TooLong  
        ?>
        <div class="bottom right">
            <input type='hidden' name='postback'></input>
            <button class="green" type='button' onclick='disableButton(this)'><i aria-hidden="true"
                    class="fa-solid fa-cloud-upload fa-lg"></i><?= t('upload.submit'); ?></button>
        </div>

    </form>
</div>
<?php } else { ?>
<div id="content">
    <h2><?= t('index.welcome'); ?></h2>
    <p><?= t('closed.text'); ?></p>
    <p><?= t('closed.apology'); ?></p>
</div>
<?php } ?>
<?php
include 'common/footer.php';
?>