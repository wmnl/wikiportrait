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
    die(t('single.not_found'));
}

$google_vision_results = DB::queryFirstRow(
    "SELECT vision_api_results.date, labels, description, matching_pages,
     matching_img, similar_img, partial_pages FROM vision_api_results
     WHERE image_id = %d ORDER BY id DESC",
    $id
);

$msg = false;
if (isset($_POST['postback'])) {
    // Unverified images only leave "Niet geverifieerd" by verification or the archive cron
    if ($row['archived'] == 2) {
        $archived = 2;
    } else {
        $archived = empty($_POST['done']) ? 0 : 1;
    }

    DB::update('images', [
        'owner'      => $_POST['owner'],
        'archived'   => $archived,
        'ticket'     => $_POST['ticket'],
        'categories' => json_encode(array_key_exists('categories', $_POST) ? $_POST['categories'] : ''),
    ], 'id = %d', $id);

    $msg = t('single.updated');
}

$owner    = DB::queryFirstRow('SELECT owner, archived, ticket, categories FROM images WHERE id = %d', $id);
$results  = DB::query('SELECT * FROM messages');
$accounts = DB::query("SELECT otrsname, id FROM users WHERE active = 1");
$verified = DB::queryFirstField("SELECT MIN(verified) FROM contributors WHERE email = %s", $row['email']);
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
    $clean = htmlspecialchars(str_replace('Category:', '', $category));
    $slug  = strtolower(preg_replace('/\s/i', '_', $clean));
    return '<input type="checkbox" name="categories[]" value="Category:' . $clean
        . '" id="categories_' . $slug . '" checked="checked">'
        . '<label for="categories_' . $slug . '">' . $clean . '</label><br>';
}

function renderGVSection(array $gv, string $imgpath, int $id): string
{
    if (filesize($imgpath) > 10_000_000) {
        return t('gv.too_large');
    }

    if (!activeGVRequests() && empty($gv['description'])) {
        return t('gv.not_activated');
    }

    $analyseAgain = t('gv.analyse_again');
    if (empty($gv['description'])) {
        return "<span>" . t('gv.no_results') . "</span> <i class='performgv' " .
            "onclick=\"performGVResults('{$id}')\">$analyseAgain</i>";
    }

    $counts = [
        'matching_pages' => countGVField($gv['matching_pages']),
        'matching_img'   => countGVField($gv['matching_img']),
        'similar_img'    => countGVField($gv['similar_img']),
        'partial_img'    => countGVField($gv['partial_img'] ?? ''),
    ];

    $html  = "<ul class='list_ml'>";
    $date        = htmlspecialchars($gv['date']);
    $labels      = htmlspecialchars($gv['labels']);
    $description = htmlspecialchars($gv['description']);

    $html .= "<li><span>" . t('gv.date') . "</span> {$date} <i class='performgv' ";
    $html .= "onclick=\"performGVResults('{$id}')\">$analyseAgain</i></li>";
    $html .= "<li><span>" . t('gv.best_label') . "</span> {$labels}</li>";
    $html .= "<li><span>" . t('gv.description') . "</span> {$description}</li>";

    $items = [
        'matching_pages' => t('gv.matching_pages'),
        'similar_img'    => t('gv.similar_img'),
        'partial_img'    => t('gv.partial_img'),
        'matching_img'   => t('gv.matching_img'),
    ];
    $show = t('js.show');
    foreach ($items as $field => $label) {
        if ($counts[$field] > 0) {
            $values = explode(',', $gv[$field] ?? '');
            $html  .= "<li class='{$field}'><span>{$label}:</span> {$counts[$field]} "
                . "<i class='toon' onclick='showGVResults(this)'>$show</i>"
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

function escapeHtml(text) {
    return $('<div>').text(text).html().replace(/"/g, '&quot;');
}

function fillCatList(data) {
    var existingCats = this;
    var $container = $('#categoriesContainer .checkbox');
    var $html = $container.html();

    $.each(data, function(index, items) {
        $.each(items, function(item, truefalse) {
            var itemcat = 'Category:' + item.replace('Category:', '');
            if ($.inArray(itemcat, existingCats) === -1) {
                var slug = escapeHtml(item.toLowerCase().replace('category:', '').replace(/\s/g, '_'));
                var name = escapeHtml(item.replace('Category:', ''));
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
    return truefalse ? '' : ' <span class="alert">' + <?= json_encode(t('single.category_missing'), JSON_HEX_TAG); ?> + '</span>';
}
</script>

<div id="content">
    <h2><?= t('single.title', ['title' => htmlspecialchars($row['title'])]); ?></h2>

    <?php if ($msg) : ?>
    <div class="box green"><?= $msg; ?></div>
    <?php endif ?>

    <div class="single">
        <div class="single-box image">
            <a href="../uploads/images/<?= htmlspecialchars($row['filename']); ?>" data-lightbox>
                <img src="../uploads/thumbs/<?= htmlspecialchars($row['filename']); ?>" />
            </a>
        </div>

        <div class="single-box info">
            <h3><?= t('common.information'); ?></h3>
            <ul class="list">
                <li><span><?= t('common.label_title'); ?></span> <?= htmlspecialchars($row['title']); ?></li>
                <li><span><?= t('single.copyright_holder'); ?></span> <?= htmlspecialchars($row['source']); ?></li>
                <li><span><?= t('single.uploaded_by'); ?></span> <?= htmlspecialchars($row['name']); ?></li>
                <li>
                    <span><?= t('single.email'); ?></span> <?= htmlspecialchars($row['email']); ?>
                    <?php if ($row['archived'] == 2) : ?>
                    <div class="not_verified">
                        <?= t('single.not_verified'); ?> &nbsp;|&nbsp;
                        <a href="/admin/verify.php?action=verify&id=<?= $row['id']; ?>&hash=<?= $row['key']; ?>"
                            class="admin-action verify-link"><?= t('single.verify_address'); ?></a>
                        &nbsp;|&nbsp;
                        <a href="/admin/verify.php?action=resend&id=<?= $row['id']; ?>&hash=<?= $row['key']; ?>"
                            class="admin-action"><?= t('single.resend_verification'); ?></a>
                    </div>
                    <?php elseif ($owner['archived'] == 1 && $verified === '0') : ?>
                    <div class="not_verified">
                        <?= t('single.not_verified'); ?> &nbsp;|&nbsp;
                        <a href="/admin/verify.php?action=unverify&id=<?= $row['id']; ?>&hash=<?= $row['key']; ?>"
                            class="admin-action unverify-link"><?= t('single.unverify'); ?></a>
                    </div>
                    <?php endif ?>
                </li>
                <li><span><?= t('single.ip'); ?></span> <?= htmlspecialchars($row['ip']); ?></li>
                <li><span><?= t('single.uploaded_on'); ?></span>
                    <?= formatDate($row['timestamp'], 'date.format_time'); ?>
                </li>
                <li><span><?= t('single.key'); ?></span><br>
                    <input type="text" id="key" disabled value="<?= htmlspecialchars($row['key']); ?>" />
                </li>
                <li><span><?= t('common.label_description'); ?></span><br><?=
                    htmlspecialchars($row['description']); ?></li>
            </ul>

            <h3><?= t('single.what_to_do'); ?></h3>
            <ul class="list">
                <li>
                    <a href="https://lens.google.com/uploadbyurl?url=<?= $imgurl; ?>&q=<?= urlencode($row['title']); ?>"
                        target="_blank">
                        <?= t('single.google_search'); ?>
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
                    <a href="<?= htmlspecialchars(getCommonsUploadLink($row)); ?>" target="_blank"><?= t('single.upload_commons'); ?></a>
                </li>
            </ul>
        </div>

        <div class="single-box options">
            <h3><?= t('single.options'); ?></h3>
            <form method="post" id="owner" name="owner">
                <?= csrfField(); ?>
                <div class="input-container">
                    <label for="setowner"><i class="fa-solid fa-user-md fa-lg"></i><?= t('single.owner'); ?></label>
                    <div class="checkbox">
                        <select class="select" name="owner" id="setowner">
                            <option value="0">----</option>
                            <?php foreach ($accounts as $account) : ?>
                            <option value="<?= $account['id']; ?>"
                                <?= $account['id'] == $owner['owner'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($account['otrsname']); ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="input-container">
                    <label for="done"><i class="fa-solid fa-check fa-lg"></i><?= t('single.done'); ?></label>
                    <div class="checkbox">
                        <?php if ($owner['archived'] == 2) : ?>
                        <input type="checkbox" name="done" id="done" disabled>
                        <label for="done"><?= t('single.verify_first'); ?></label>
                        <?php else : ?>
                        <input type="checkbox" name="done" id="done" <?= $owner['archived'] == 1 ? 'checked' : ''; ?>>
                        <label for="done"><?= t('common.yes'); ?></label>
                        <?php endif ?>
                    </div>
                </div>

                <div class="input-container">
                    <label for="ticket"><i class="fa-solid fa-ticket fa-lg"></i><?= t('single.ticket'); ?></label>
                    <input type="text" name="ticket" id="ticket" value="<?= htmlspecialchars($owner['ticket'] ?? ''); ?>">
                </div>

                <div class="input-container">
                    <label><?= t('single.categories'); ?></label>
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
                    $catJson      = htmlspecialchars(json_encode([$row['title'], $row['source']]));
                    $existingCats = htmlspecialchars($owner['categories'] ?: '[]');
                    ?>
                    <button type="button" onclick="getCategories(<?= $catJson; ?>, <?= $existingCats; ?>)">
                        <i class="fa-solid fa-list-alt" aria-hidden="true"></i> <?= t('single.get_categories'); ?>
                    </button>
                    <button type="submit" formaction="get.php?id=<?= $id; ?>" name="claim">
                        <i class="fa-solid fa-bolt fa-lg"></i><?= t('single.claim'); ?>
                    </button>
                    <span class="divider">&nbsp;</span>
                    <button class="green" type="submit" name="postback">
                        <i class="fa-solid fa-floppy-o fa-lg"></i><?= t('common.save'); ?>
                    </button>
                </div>
            </form>

            <div>
                <h3><?= t('gv.title'); ?></h3>
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
const singleMessages = <?= json_encode([
    'busy' => t('single.busy'),
    'verified' => t('single.verified'),
    'resendShort' => t('single.resend_short'),
    'error' => t('error.generic'),
], JSON_HEX_TAG | JSON_UNESCAPED_UNICODE); ?>;
$(document).on('click', '.admin-action', function(e) {
    e.preventDefault();
    const $link = $(this);
    const label = $link.text();
    $link.text(singleMessages.busy);

    $.getJSON($link.attr('href'))
        .done(function(data) {
            if ($link.hasClass('verify-link')) {
                $link.closest('.not_verified').replaceWith($('<span>').text(singleMessages.verified));
            } else if ($link.hasClass('unverify-link')) {
                setTimeout(() => location.reload(), 1500);
            } else {
                $link.text(singleMessages.resendShort);
            }
            toonMelding(data.message, 'success');
        })
        .fail(function() {
            $link.text(label);
            toonMelding(singleMessages.error, 'error');
        });
});
</script>

<?php include '../common/footer.php'; ?>