<?php
require '../common/bootstrap.php';
$session->checkLogin();
if (empty($_GET['id'])) {
    $session->redirect("/images/overview");
}

require '../common/header.php';
$msg = false;
?>
<script>
    function getCategories(cats, existingCats) {
        getData(cats).success(
                fillCatList.bind(existingCats)
                );
    }
    function getData(cats) {
        return $.ajax({
            url: 'categories.php',
            type: 'POST',
            data: {cat: cats},
            dataType: "json"
        });

    }
    function fillCatList(data) {
        var existingCats = this;
        $html = $('#categoriesContainer .checkbox').html();
        $.each(data,
                function (index, items) {
                    $.each(items, function (item, truefalse) {
                        var itemcat = 'Category:' + item.replace('Category:', '');
                        if ($.inArray(itemcat, existingCats) == -1) {
                            $html += '<input type="checkbox" name="categories[]" value="Category:' +
                                    item.replace('Category:', '') + '" id="categories_' +
                                                                item.toLowerCase().replace(
                                                                'category:', '').replace(/\s/g, '_') + '">\n\
                        <label for="categories_' + item.toLowerCase().replace('category:', '').replace(/\s/g, '_')
                                                                + '">' + item.replace('Category:', '') +
                                                                getTrueFalse(truefalse) + '</label><br>';
                        }
                    }.bind(this))
                }.bind(this));
        $('#categoriesContainer .checkbox').html($html);
    }
    function getTrueFalse(truefalse) {
        if (!truefalse) {
            return ' <span class="alert">Categorie bestaat niet</span>';
        } else {
            return '';
        }
    }
</script>
<div id="content">
    <?php
    $row = DB::queryFirstRow("SELECT * FROM images WHERE id = %d", $_GET['id']);

    if (DB::count() == 0) {
        die("Foto niet gevonden!");
    }

    $google_vision_results = DB::queryFirstRow(
        "SELECT vision_api_results.date, labels, description, matching_pages,
      matching_img, similar_img, partial_pages FROM vision_api_results WHERE image_id = %d ORDER BY id DESC",
        $_GET['id']
    );

    if (isset($_POST['postback'])) {
        DB::update('images', [
            'owner' => $_POST['owner'],
            'archived' => !empty($_POST['done']),
            'ticket' => $_POST['ticket'],
            'categories' => json_encode($_POST['categories'])
                ], 'id = %d', $_GET['id']);

            $msg = 'Afbeelding is bijgewerkt. <a href="overview.php">Terug naar overzicht</a>';
    }
    ?>

    <h2>Ingestuurde foto: <?= $row['title']; ?></h2>

    <?php if ($msg) : ?>
    <div class="box green"><?= $msg; ?></div>
    <?php endif; ?>

    <div class="single">
        <div class="single-box image">
            <a href="../uploads/images/<?= $row['filename']; ?>" data-lightbox>
                <img src="../uploads/thumbs/<?= $row['filename']; ?>" />
            </a>
        </div>

        <div class="single-box info">
            <h3>Informatie</h3>

            <ul class="list">
                <li><span>Titel:</span> <?= htmlspecialchars($row['title']); ?></li>
                <li><span>Rechthebbende:</span> <?= htmlspecialchars($row['source']); ?></li>
                <li><span>Geüpload door:</span> <?= htmlspecialchars($row['name']); ?></li>
                <li><span>E-mailadres:</span> <?= htmlspecialchars($row['email']); ?></li>
                <li><span>IP-adres:</span> <?= $row['ip']; ?></li>
                <li><span>Geüpload op:</span> <?= strftime("%e %B %Y om %H:%I:%S", $row['timestamp']) ?></li>
                <li><span>Key:</span><br /><input type="text" id="key" disabled="disabled" value="<?= $row['key']; ?>"
                                                  /></li>
                <li><span>Beschrijving:</span><br /><?= htmlspecialchars($row['description']); ?></li>
            </ul>

            <h3>Wat doen we ermee?</h3>

            <ul class="list">
                <li>
                    <?php
                    $imgurl = urlencode(BASE_URL . "/uploads/images/" . $row['filename']);
                    $imgpath = ABSPATH . "/uploads/images/" . $row['filename'];
                    ?>
                    <a href="https://www.google.com/searchbyimage?image_url=<?= $imgurl ?>&q=<?= urlencode($row['title']); ?>" target="_blank">Zoek naar
                        deze afbeelding bij Google</a>
                </li>
                <?php
                $results = DB::query('SELECT * FROM messages');
                $owner = DB::queryFirstRow(
                    'SELECT owner, archived,ticket,categories FROM images WHERE id = %d',
                    $_GET['id']
                );
                $accounts = DB::query("SELECT otrsname, id FROM users WHERE active = 1");

                foreach ($results as $resultRow) :
                    ?>
                <li><a href="message.php?message=<?= $resultRow['id']; ?>&image=<?= $_GET['id'] ?>"
                           ><?= htmlspecialchars($resultRow['title']); ?></a></li>
                    <?php
                endforeach;
                ?>
                <li>
                    <a href="<?php echo getCommonsUploadLink($row); ?>" target="_blank">
                        Uploaden naar Commons!
                    </a>
                </li>
            </ul>
        </div>

        <div class="single-box options">
            <h3>Opties</h3>

            <form method="post" id="owner" name="owner">
                <div class="input-container">
                    <label for="owner"><i class="fa fa-user-md fa-lg fa-fw"></i>Eigenaar</label>

                    <div class="checkbox">
                        <select class="select" name="owner" id="setowner">
                            <option value="0">----</option>
                            <?php
                            foreach ($accounts as $rowdata) :
                                $selected = "";
                                if ($rowdata['id'] == $owner['owner']) {
                                    $selected = 'selected="selected"';
                                }
                                ?>
                            <option value="<?= $rowdata['id'] ?>" <?= $selected ?>><?= $rowdata['otrsname'] ?></option>
                                <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="input-container">
                    <label for="done"><i class="fa fa-check fa-lg fa-fw"></i>Afgehandeld</label>
                    <div class="checkbox">
                        <input type="checkbox" name="done" id="done" <?php
                        if ($owner['archived'] == 1) {
                            echo "checked";
                        }
                        ?> /><label for="done">Ja</label>
                    </div>
                </div>

                <div class="input-container">
                    <label for="ticket"><i class="fa fa-ticket fa-lg fa-fw"></i>OTRS-ticket:</label>
                    <input type="text" name="ticket" id="ticket" value="<?= $owner['ticket']; ?>">
                </div>

                <div class="input-container">
                    <label for="categories">Categorieën: </label>
                    <div id="categoriesContainer"><div class="checkbox"><?php
                    if (array_key_exists('categories', $owner)) {
                        $categoriesList = json_decode($owner['categories']);
                        if (is_array($categoriesList)) {
                            foreach ($categoriesList as $category) {
                                echo '	<input type="checkbox" name="categories[]" value="Category:' .
                                str_replace('Category:', '', $category) . '" id="categories_' .
                                strtolower(preg_replace('/\s/i', '_', str_replace('Category:', '', $category)))
                                . '" checked="checked"><label for="categories_' . strtolower(preg_replace(
                                    '/\s/i',
                                    '_',
                                    str_replace('Category:', '', $category)
                                )) . '">' . str_replace(
                                    'Category:',
                                    '',
                                    $category
                                ) . '</label><br>';
                            }
                        }
                    }
                    ?></div></div>
                </div>
                <div class="bottom right">
                    <button type="button" onclick="getCategories(<?php
                    echo str_replace(
                        '"',
                        '\'',
                        json_encode(array($row['title'], $row['source']))
                    ) . ',' . str_replace(
                        '"',
                        '\'',
                        $owner['categories'] ?? ''
                    );
                    ?>)"> <i class="fa fa-list-alt" aria-hidden="true"
                          ></i> Krijg categorieën
                    </button>
                    <button type="button" onClick="parent.location = 'get.php?id=<?= $_GET['id'] ?>'" name="claim">
                        <i class="fa fa-bolt fa-lg"></i>Ik neem hem
                    </button>

                    <span class="divider">&nbsp;</span>

                    <button class="green" type="submit" name="postback">
                        <i class="fa fa-floppy-o fa-lg"></i>Opslaan
                    </button>
                </div>
            </form>

            <div>
                <h3>Google Vision Analysis</h3>
                <div id='ml_resp'>
                    <?php
                    if (filesize($imgpath) > 10000000) {
                        echo "Geen analyse (Bestand is groter dan 10MB)";
                    } else {
                        if (activeGVRequests() || !empty($google_vision_results['description'])) {
                            if (!empty($google_vision_results['description'])) {
                                $matching_pages = explode(',', $google_vision_results['matching_pages']);
                                (!empty($google_vision_results['matching_pages'])) ?
                                $mathing_p_count = count($matching_pages) : $mathing_p_count = 0;
                                $matching_img = explode(',', $google_vision_results['matching_img']);
                                (!empty($google_vision_results['matching_img'])) ?
                                $mathing_i_count = count($matching_img) : $mathing_i_count = 0;
                                $similar_img = explode(',', $google_vision_results['similar_img']);
                                (!empty($google_vision_results['similar_img'])) ?
                                $similar_i_count = count($similar_img) : $similar_i_count = 0;
                                $partial_img = explode(',', $google_vision_results['partial_img']);
                                (!empty($google_vision_results['partial_img'])) ? $partialcount = count($partial_img) :
                                $partialcount = 0;

                                ?>
                    <ul class="list_ml">
                        <li><span>Datum analyse:</span> <?php echo $google_vision_results['date']; ?> <i
                                            class='performgv' onclick="performGVResults('<?php echo $_GET['id']; ?>')"
                                            >Nog een keer analyseren</i></li>
                                    <li><span>Beste categorie:</span> <?php echo $google_vision_results['labels'];
                                    ?></li>
                                    <li><span>Beschrijving:</span> <?php echo $google_vision_results['description'];
                                    ?></li>
                                    <?php
                                    if ($mathing_p_count > 0) {
                                        echo "<li class='matching_pages'><span>Aantal webpaginas met dezelfde "
                                                    . "afbeelding:</span> $mathing_p_count <i class='toon' onclick='"
                                                    . "showGVResults(this)'>tonen</i>";
                                                    echo makeGVResults($matching_pages);
                                        echo "</li>";
                                    }
                                    if ($similar_i_count > 0) {
                                        echo "<li class='similar_img'><span>Aantal vergelijkbare afbeeldingen:</span> "
                                                    . "$similar_i_count <i class='toon' "
                                        . "onclick='showGVResults(this)'>tonen</i>";
                                        echo makeGVResults($similar_img);
                                        echo "</li>";
                                    }
                                    if ($partialcount > 0) {
                                        echo "<li class='partial_img'><span>Aantal gedeeltelijke afbeeldingen:</span> "
                                                    . "$partialcount <i class='toon' "
                                        . "onclick='showGVResults(this)'>tonen</i>";
                                        echo makeGVResults($partial_img);
                                        echo "</li>";
                                    }
                                    if ($mathing_i_count > 0) {
                                        echo "<li class='matching_img'><span>Aantal gedeeltelijke afbeeldingen:</span> "
                                                    . "$mathing_i_count <i class='toon' "
                                        . "onclick='showGVResults(this)'>tonen</i>";
                                        echo makeGVResults($matching_img);
                                        echo "</li></ul>";
                                    }
                            } else { // Analysis results are empty....
                                echo "<span>Geen resultaten</span> <i class='performgv' onclick=\"performGVResults('"
                                                . $_GET['id'] . "')\">Nog een keer analyseren</i>";
                            }
                        } else { // Analysis results are empty....
                            echo "Niet geactiveerd";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $basispad ?>/lib/imagelightbox/dist/imagelightbox.min.js"></script>
<script src="<?= $basispad ?>/js/lightbox.js"></script>
<script src="<?= $basispad ?>/js/ml.js"></script>

<?php
include '../common/footer.php';
?>
