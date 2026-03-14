<?php
//@phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
require_once '../common/bootstrap.php';
$session->checkLogin();

if (empty($_GET['id'])) {
    $session->redirect("/images/overview");
}

require_once '../common/header.php';

$id  = (int) $_GET['id'];
$row = DB::queryFirstRow("SELECT * FROM images WHERE id = %d", $id);

if (!$row) {
    die("Foto niet gevonden!");
}

$google_vision_results = DB::queryFirstRow(
    "SELECT vision_api_results.date, labels, description, matching_pages,
     matching_img, similar_img, partial_pages FROM vision_api_results
     WHERE image_id = %d ORDER BY id DESC",
    $id
);

$msg = false;
if (isset($_POST['postback'])) {
    DB::update('images', [
        'owner'      => $_POST['owner'],
        'archived'   => !empty($_POST['done']),
        'ticket'     => $_POST['ticket'],
        'categories' => json_encode(array_key_exists('categories', $_POST) ? $_POST['categories'] : ''),
    ], 'id = %d', $id);

    $msg = 'Afbeelding is bijgewerkt. <a href="overview.php">Terug naar overzicht</a>';
}

$owner    = DB::queryFirstRow('SELECT owner, archived, ticket, categories FROM images WHERE id = %d', $id);
$results  = DB::query('SELECT * FROM messages');
$accounts = DB::query("SELECT otrsname, id FROM users WHERE active = 1");
$imgurl   = urlencode(BASE_URL . "/uploads/images/" . $row['filename']);
$imgpath  = ABSPATH . "/uploads/images/" . $row['filename'];

$categoriesList = [];
if (array_key_exists('categories', $owner)) {
    $decoded = json_decode($owner['categories']);
    if (is_array($decoded)) {
        $categoriesList = $decoded;
    }
}

function renderCategory(string $category): string
{
    $clean = str_replace('Category:', '', $category);
    $slug  = strtolower(preg_replace('/\s/i', '_', $clean));
    return '<input type="checkbox" name="categories[]" value="Category:' . $clean
        . '" id="categories_' . $slug . '" checked="checked">'
        . '<label for="categories_' . $slug . '">' . $clean . '</label><br>';
}

function renderGVSection(array $gv, string $imgpath, int $id): string
{
    if (filesize($imgpath) > 10_000_000) {
        return "Geen analyse (Bestand is groter dan 10MB)";
    }

    if (!activeGVRequests() && empty($gv['description'])) {
        return "Niet geactiveerd";
    }

    if (empty($gv['description'])) {
        return "<span>Geen resultaten</span> <i class='performgv' " .
            "onclick=\"performGVResults('{$id}')\">Nog een keer analyseren</i>";
    }

    $counts = [
        'matching_pages' => countGVField($gv['matching_pages']),
        'matching_img'   => countGVField($gv['matching_img']),
        'similar_img'    => countGVField($gv['similar_img']),
        'partial_img'    => countGVField($gv['partial_img'] ?? ''),
    ];

    $html  = "<ul class='list_ml'>";
    $html .= "<li><span>Datum analyse:</span> {$gv['date']} <i class='performgv' ";
    $html .= "onclick=\"performGVResults('{$id}')\">Nog een keer analyseren</i></li>";
    $html .= "<li><span>Beste categorie:</span> {$gv['labels']}</li>";
    $html .= "<li><span>Beschrijving:</span> {$gv['description']}</li>";

    $items = [
        'matching_pages' => 'Aantal webpaginas met dezelfde afbeelding',
        'similar_img'    => 'Aantal vergelijkbare afbeeldingen',
        'partial_img'    => 'Aantal gedeeltelijke afbeeldingen',
        'matching_img'   => 'Aantal exacte afbeeldingen',
    ];
    foreach ($items as $field => $label) {
        if ($counts[$field] > 0) {
            $values = explode(',', $gv[$field] ?? '');
            $html  .= "<li class='{$field}'><span>{$label}:</span> {$counts[$field]} "
                . "<i class='toon' onclick='showGVResults(this)'>tonen</i>"
                . makeGVResults($values) . "</li>";
        }
    }

    return $html . "</ul>";
}

function countGVField(string $value): int
{
    return empty($value) ? 0 : count(explode(',', $value));
}
?>

<script>
function getCategories(cats, existingCats) {
    getData(cats)
        .done(function(data) {
            fillCatList.call(existingCats, data);
        })
        .fail(function(xhr) {
            console.error('ERROR', xhr.responseText);
        });
}

function getData(cats) {
    return $.ajax({
        url: 'categories.php',
        type: 'POST',
        data: {
            cat: cats
        },
        dataType: 'json'
    });
}

function fillCatList(data) {
    var existingCats = this;
    var $container = $('#categoriesContainer .checkbox');
    var $html = $container.html();

    $.each(data, function(index, items) {
        $.each(items, function(item, truefalse) {
            var itemcat = 'Category:' + item.replace('Category:', '');
            if ($.inArray(itemcat, existingCats) === -1) {
                var slug = item.toLowerCase().replace('category:', '').replace(/\s/g, '_');
                var name = item.replace('Category:', '');
                $html += '<input type="checkbox" name="categories[]" value="Category:' + name +
                    '" id="categories_' + slug + '">\n' +
                    '<label for="categories_' + slug + '">' + name + getTrueFalse(truefalse) +
                    '</label><br>';
            }
        }.bind(this));
    }.bind(this));

    $container.html($html);
}

function getTrueFalse(truefalse) {
    return truefalse ? '' : ' <span class="alert">Categorie bestaat niet</span>';
}
</script>

<div id="content">
    <h2>Ingestuurde foto: <?= htmlspecialchars($row['title']); ?></h2>

    <?php if ($msg) : ?>
    <div class="box green"><?= $msg; ?></div>
    <?php endif ?>

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
                <li>
                    <span>E-mailadres:</span> <?= htmlspecialchars($row['email']); ?>
                    <?php if ($row['archived'] == 2) : ?>
                    <div class="not_verified">
                        Niet geverifieerd &nbsp;|&nbsp;
                        <a href="/admin/verify.php?action=verify&id=<?= $row['id']; ?>&hash=<?= $row['key']; ?>"
                            class="admin-action verify-link">Verifieer adres</a>
                        &nbsp;|&nbsp;
                        <a href="/admin/verify.php?action=resend&id=<?= $row['id']; ?>&hash=<?= $row['key']; ?>"
                            class="admin-action">Stuur verificatiemail opnieuw</a>
                    </div>
                    <?php endif ?>
                </li>
                <li><span>IP-adres:</span> <?= $row['ip']; ?></li>
                <li><span>Geüpload op:</span>
                    <?=
                    (new IntlDateFormatter(
                        'nl_NL',
                        IntlDateFormatter::NONE,
                        IntlDateFormatter::NONE,
                        null,
                        null,
                        "d MMMM yyyy 'om' HH:mm:ss"
                    ))->format($row['timestamp']);
?>
                </li>
                <li><span>Key:</span><br>
                    <input type="text" id="key" disabled value="<?= $row['key']; ?>" />
                </li>
                <li><span>Beschrijving:</span><br><?= htmlspecialchars($row['description']); ?></li>
            </ul>

            <h3>Wat doen we ermee?</h3>
            <ul class="list">
                <li>
                    <a href="https://lens.google.com/uploadbyurl?url=<?= $imgurl; ?>&q=<?= urlencode($row['title']); ?>"
                        target="_blank">
                        Zoek naar deze afbeelding bij Google
                    </a>
                </li>
                <?php foreach ($results as $resultRow) : ?>
                <li>
                    <a href="message.php?message=<?= $resultRow['id']; ?>&image=<?= $id; ?>">
                        <?= htmlspecialchars($resultRow['title']); ?>
                    </a>
                </li>
                <?php endforeach ?>
                <li>
                    <a href="<?= getCommonsUploadLink($row); ?>" target="_blank">Uploaden naar Commons!</a>
                </li>
            </ul>
        </div>

        <div class="single-box options">
            <h3>Opties</h3>
            <form method="post" id="owner" name="owner">
                <div class="input-container">
                    <label for="setowner"><i class="fa-solid fa-user-md fa-lg"></i>Eigenaar</label>
                    <div class="checkbox">
                        <select class="select" name="owner" id="setowner">
                            <option value="0">----</option>
                            <?php foreach ($accounts as $account) : ?>
                            <option value="<?= $account['id']; ?>"
                                <?= $account['id'] == $owner['owner'] ? 'selected' : ''; ?>>
                                <?= $account['otrsname']; ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="input-container">
                    <label for="done"><i class="fa-solid fa-check fa-lg"></i>Afgehandeld</label>
                    <div class="checkbox">
                        <input type="checkbox" name="done" id="done" <?= $owner['archived'] == 1 ? 'checked' : ''; ?>>
                        <label for="done">Ja</label>
                    </div>
                </div>

                <div class="input-container">
                    <label for="ticket"><i class="fa-solid fa-ticket fa-lg"></i>VRTS-ticket:</label>
                    <input type="text" name="ticket" id="ticket" value="<?= $owner['ticket']; ?>">
                </div>

                <div class="input-container">
                    <label>Categorieën:</label>
                    <div id="categoriesContainer">
                        <div class="checkbox">
                            <?php foreach ($categoriesList as $category) : ?>
                                <?= renderCategory($category); ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <div class="bottom right">
                    <?php
                    $catJson      = str_replace('"', "'", json_encode([$row['title'], $row['source']]));
                    $existingCats = str_replace('"', "'", $owner['categories'] ?? '');
                    ?>
                    <button type="button" onclick="getCategories(<?= $catJson; ?>, <?= $existingCats; ?>)">
                        <i class="fa-solid fa-list-alt" aria-hidden="true"></i> Krijg categorieën
                    </button>
                    <button type="button" onclick="parent.location='get.php?id=<?= $id; ?>'" name="claim">
                        <i class="fa-solid fa-bolt fa-lg"></i>Ik neem hem
                    </button>
                    <span class="divider">&nbsp;</span>
                    <button class="green" type="submit" name="postback">
                        <i class="fa-solid fa-floppy-o fa-lg"></i>Opslaan
                    </button>
                </div>
            </form>

            <div>
                <h3>Google Vision Analysis</h3>
                <div id="ml_resp">
                    <?= renderGVSection($google_vision_results ?? [], $imgpath, $id); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $basispad; ?>/lib/imagelightbox/dist/imagelightbox.min.js"></script>
<script src="<?= $basispad; ?>/js/lightbox.js"></script>
<script src="<?= $basispad; ?>/js/ml.js"></script>
<script>
$(document).on('click', '.admin-action', function(e) {
    e.preventDefault();
    const $link = $(this);
    $link.text('Bezig...');

    $.getJSON($link.attr('href'))
        .done(function(data) {
            if ($link.hasClass('verify-link')) {
                $link.closest('.not_verified').replaceWith('<span>Geverifieerd</span>');
            } else {
                $link.text('Stuur mail opnieuw');
            }
            toonMelding(data.message, 'success');
        })
        .fail(function() {
            $link.text($link.hasClass('verify-link') ? 'Verifieer adres' : 'Stuur verificatiemail opnieuw');
            toonMelding('Er is iets misgegaan, probeer het opnieuw.', 'error');
        });
});

function toonMelding(tekst, type) {
    const $melding = $('<div>').text(tekst).addClass('melding ' + type);
    $('body').append($melding);
    setTimeout(() => $melding.remove(), 4000);
}
</script>

<?php include '../common/footer.php'; ?>