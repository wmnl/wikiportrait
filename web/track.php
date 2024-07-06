<?php
    require 'common/bootstrap.php';
    require 'common/header.php';
?>
<div id="content">
    <?php
    if (empty($_GET['key'])) :
        echo "<div class=\"box red\">Geen afbeelding gevonden!</div>";
    else :
        $row = DB::queryFirstRow("SELECT * FROM images WHERE `key` = %s", $_GET['key']);

        if (DB::count() == 0) :
            echo "<div class=\"box red\">Geen afbeelding gevonden!</div>";
        else :
            ?>

    <h2>Inzending volgen: <?php echo $row['title']; ?> </h2>

    <div class="single">
        <div class="single-box image">
            <a href="uploads/images/<?php echo $row['filename']; ?>" data-lightbox>
                <img src="uploads/thumbs/<?php echo $row['filename'] ; ?>" />
            </a>
        </div>

        <div class="single-box info">
            <h3>Informatie</h3>

            <div class="holder">
            <div><span class="title">Titel:</span><span class="content"><?= htmlspecialchars($row['title']); ?></span></div>
            <div><span class="title">Auteursrechthebbende:</span><span class="content"><?= htmlspecialchars($row['source']); ?></span></div>
            <div><span class="title">Geupload door:</span><span class="content"><?= htmlspecialchars($row['name']); ?></span></div>
            <div><span class="title">Beschrijving:</span><span class="content"><?= htmlspecialchars($row['description']);?></span></div>
            </div>
        </div>

        <div class="single-box options">

            <h3>Status</h3>

            <?php
            if ($row['archived'] == 1) {
                echo "<div class=\"box green\"><i class=\"fa fa-check fa-lg\"></i>Afgehandeld</div>";
            } elseif ($row['archived'] == 0 && $row['owner'] != 0) {
                echo "<div class=\"box grey\"><i class=\"fa fa-clock-o fa-lg\"></i>In behandeling</div>";
            } elseif ($row['archived'] == 0 && $row['owner'] == 0) {
                echo "<div class=\"box grey\"><i class=\"fa fa-clock-o fa-lg\"></i>In de wachtrij</div>";
            } else {
                echo "<div class=\"box red\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i>Status onbekend</div>";
            }
            ?>
            <?php if ($session->isLoggedIn()) { ?>
                <a href="images/single.php?id=<?php echo $row['id']; ?>"><button class="blue">Bekijk</button></a>
            <?php } ?>
        </div>
        </div>

            <?php
        endif;
    endif;
    ?>

</div>

<script src="<?= $basispad ?>/lib/imagelightbox/dist/imagelightbox.min.js"></script>
<script src="<?= $basispad ?>/js/lightbox.js"></script>

<?php
    include 'common/footer.php';
?>