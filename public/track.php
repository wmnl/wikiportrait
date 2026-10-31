<?php
require_once 'common/bootstrap.php';
require_once 'common/header.php';
?>
<div id="content">
    <?php
    if (empty($_GET['key'])) :
        echo "<div class=\"box red\">" . t('track.not_found') . "</div>";
    else :
        $row = DB::queryFirstRow("SELECT * FROM images WHERE `key` = %s", $_GET['key']);

        if (DB::count() == 0) :
            echo "<div class=\"box red\">" . t('track.not_found') . "</div>";
        else :
            ?>

            <h2><?= t('track.title', ['title' => htmlspecialchars($row['title'])]); ?> </h2>

            <div class="single">
                <div class="single-box image">
                    <a href="uploads/images/<?= htmlspecialchars($row['filename']); ?>" data-lightbox>
                        <img src="uploads/thumbs/<?= htmlspecialchars($row['filename']); ?>" />
                    </a>
                </div>

                <div class="single-box info">
                    <h3><?= t('common.information'); ?></h3>

                    <div class="holder">
                        <div><span class="title"><?= t('common.label_title'); ?></span><span class="content"><?= htmlspecialchars($row['title']); ?></span></div>
                        <div><span class="title"><?= t('track.copyright_holder'); ?></span><span class="content"><?= htmlspecialchars($row['source']); ?></span></div>
                        <div><span class="title"><?= t('track.uploaded_by'); ?></span><span class="content"><?= htmlspecialchars($row['name']); ?></span></div>
                        <div><span class="title"><?= t('common.label_description'); ?></span><span class="content"><?= htmlspecialchars($row['description']); ?></span></div>
                    </div>
                </div>

                <div class="single-box options">

                    <h3><?= t('track.status'); ?></h3>

                    <?php
                    if ($row['archived'] == 1) {
                        echo "<div class=\"box green\"><i class=\"fa-solid fa-check fa-lg\"></i>" . t('track.status_done') . "</div>";
                    } elseif ($row['archived'] == 0 && $row['owner'] != 0) {
                        echo "<div class=\"box grey\"><i class=\"fa-solid fa-clock fa-lg\"></i>" . t('track.status_in_progress') . "</div>";
                    } elseif ($row['archived'] == 0 && $row['owner'] == 0) {
                        echo "<div class=\"box grey\"><i class=\"fa-solid fa-clock fa-lg\"></i>" . t('track.status_queue') . "</div>";
                    } else {
                        echo "<div class=\"box red\"><i class=\"fa-solid fa-exclamation-triangle fa-lg\"></i>" . t('track.status_unknown') . "</div>";
                    }
                    ?>
                    <?php if ($session->isLoggedIn()) { ?>
                        <a href="images/single.php?id=<?php echo $row['id']; ?>"><button class="blue"><?= t('track.view'); ?></button></a>
                    <?php } ?>
                </div>
            </div>

            <?php
        endif;
    endif;
    ?>

</div>

<script src="<?= $basispad; ?>/lib/imagelightbox/dist/imagelightbox.min.js"></script>
<script src="<?= $basispad; ?>/js/lightbox.js"></script>

<?php
include 'common/footer.php';
?>