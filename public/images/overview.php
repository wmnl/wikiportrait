<?php
require_once '../common/bootstrap.php';
$session->checkLogin();
require_once '../common/header.php';

include 'tabs.php';

$archived = isset($_GET['archived']) && $_GET['archived'] != 0;
$archive = $archived ? $_GET['archived'] : 0;

$bulkActions = match ((int) $archive) {
    0 => [
        ['endpoint' => 'bulk.php', 'action' => 'archive', 'icon' => 'fa-archive', 'label' => 'Archiveren'],
    ],
    1 => $session->isSysop() ? [
        ['endpoint' => '../admin/verify.php', 'action' => 'unverify', 'icon' => 'fa-undo',
            'label' => 'Terugzetten naar Niet geverifieerd'],
    ] : [],
    2 => $session->isSysop() ? [
        ['endpoint' => '../admin/verify.php', 'action' => 'verify', 'icon' => 'fa-check', 'label' => 'Verifieer adres'],
        ['endpoint' => '../admin/verify.php', 'action' => 'resend', 'icon' => 'fa-envelope',
            'label' => 'Stuur verificatiemail opnieuw'],
    ] : [],
    default => [],
};

// Only archived images whose address was never verified can go back to "Niet geverifieerd"
function isSelectable(int $archive, ?string $verified): bool
{
    return $archive !== 1 || $verified === '0';
}
?>
<div id="content">
    <div class="page-header">

        <?php
        if ($archived == 0) {
            echo "<h2>Inzendingen</h2>";
        }

        if ($archived == 1) {
            echo "<h2>Archief</h2>";
        }

        if (isset($_GET['personal'])) {
            $total_records = DB::queryFirstField(
                'SELECT COUNT(*) FROM images WHERE archived = %d AND owner = \'%s\'',
                $archive,
                $_SESSION['user']
            );
        } else {
            $total_records = DB::queryFirstField('SELECT COUNT(*) FROM images WHERE archived = %d', $archive);
        }

        $total_pages = ceil($total_records / 25);

        if ($total_pages > 1) :
            ?>
            <form class="navigation" method="post">
                <select class="select" name="page" onchange="loadPage()" id="page">
                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) :
                        ?>
                        <option value='<?= $i; ?>' 
                        <?php
                        if (isset($_GET['page']) && $_GET['page'] == $i) {
                            echo 'selected';
                        }
                        ?>
                                                    ><?= $i; ?></option>
                        <?php
                    endfor;
                    ?>
                </select>
            </form>

            <?php
        endif;
        ?>
    </div>

    <?php if ($bulkActions) : ?>
    <div class="bulk-actions">
        <span class="bulk-count">0 geselecteerd</span>
        <?php foreach ($bulkActions as $bulkAction) : ?>
        <button type="button" class="bulk-action" disabled
            data-endpoint="<?= $bulkAction['endpoint']; ?>" data-action="<?= $bulkAction['action']; ?>">
            <i class="fa-solid <?= $bulkAction['icon']; ?>"></i><?= $bulkAction['label']; ?>
        </button>
        <?php endforeach ?>
    </div>
    <?php endif ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <?php if ($bulkActions) : ?>
                    <th class="select-container"><input type="checkbox" id="select-all" title="Alles selecteren"></th>
                    <?php endif ?>
                    <th class="img-container">Foto</th>
                    <th>Titel</th>
                    <th>Uploader</th>
                    <th>Datum</th>
                    <th>Eigenaar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                setlocale(LC_ALL, 'nl_NL');
                date_default_timezone_set('Europe/Amsterdam');
                if (isset($_GET['page'])) {
                    $page   = $_GET['page'];
                } else {
                    $page = 1;
                };
                $start_from = ($page - 1) * 25;
                if (isset($_GET['personal'])) {
                    $results = DB::query(
                        "SELECT images.id as image_id, images.title as title, images.filename as "
                            . "filename, images.name as name, images.timestamp as timestamp, users.otrsname "
                            . "as otrsname, (SELECT MIN(c.verified) FROM contributors c WHERE c.email = images.email) "
                            . "as verified FROM images LEFT JOIN users ON users.id = owner WHERE archived = %d AND "
                            . "owner = '%s' ORDER BY images.id DESC LIMIT %d, 25",
                        $archive,
                        $_SESSION['user'],
                        $start_from
                    );
                } else {
                    $results = DB::query("SELECT images.id as image_id, images.title as title, images.filename as "
                        . "filename, images.name as name, images.timestamp as timestamp, "
                        . "users.otrsname as otrsname, "
                        . "(SELECT MIN(c.verified) FROM contributors c WHERE c.email = images.email) as verified "
                        . "FROM images LEFT JOIN users ON users.id = owner "
                        . "WHERE archived = %d ORDER BY images.id DESC LIMIT %d, 25", $archive, $start_from);
                }

                foreach ($results as $row) :
                    $id = $row['image_id'];
                    $filename = $row['filename'];
                    $title = $row['title'];
                    $name = $row['name'];
                    $timestamp = $row['timestamp'];
                    if (empty($row['otrsname'])) {
                        $owner = "Aan niemand toegewezen";
                    } else {
                        $owner = $row['otrsname'];
                    }
                    $disabled = isSelectable((int) $archive, $row['verified'])
                        ? '' : 'disabled title="Mailadres is geverifieerd"';
                    ?>
                    <tr>
                        <?php if ($bulkActions) : ?>
                        <td data-title="&#xf14a;" class="select">
                            <input type="checkbox" class="bulk-select" value="<?= $id; ?>" <?= $disabled; ?>>
                        </td>
                        <?php endif ?>
                        <td data-title="&#xf03e;" class="image"><a href="single.php?id=<?php echo $id; ?>">
                                <img src="../uploads/thumbs/<?php echo $filename; ?>" /></a></td>
                        <td data-title="&#xf02b;"><a
                                href="single.php?id=<?php echo $id; ?>"><?= htmlentities($title); ?></a>
                        </td>
                        <td data-title="&#xf007;"><?= htmlentities($name); ?></td>
                        <td data-title="&#xf073;">
                        <?=
                        (new IntlDateFormatter(
                            'nl_NL',
                            IntlDateFormatter::NONE,
                            IntlDateFormatter::NONE,
                            null,
                            null,
                            "d MMMM yyyy"
                        ))->format($timestamp);
                        ?>
                                                    </td>
                        <td data-title="&#xf0f0;"><?= $owner; ?></td>
                    </tr>
                    <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++) {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return sParameterName[1];
            }
        }
    }

    var page = getUrlParameter('page');
    var archived = getUrlParameter('archived');

    if (page == undefined) {
        page = 1;
    }

    if (archived == undefined) {
        archived = 0;
    }

    function loadPage() {
        window.location.href = 'overview.php?page=' + document.getElementById('page').value + '&archived=' + archived;
    }

    function updateBulkActions() {
        const count = $('.bulk-select:checked').length;
        const $selectable = $('.bulk-select:enabled');
        $('.bulk-count').text(count + ' geselecteerd');
        $('.bulk-action').prop('disabled', count === 0);
        $('#select-all').prop('checked', count > 0 && count === $selectable.length);
    }

    $('#select-all').on('change', function() {
        $('.bulk-select:enabled').prop('checked', this.checked);
        updateBulkActions();
    });

    $('.bulk-select').on('change', updateBulkActions);

    $('.bulk-action').on('click', function() {
        const $button = $(this);
        const ids = $('.bulk-select:checked').map((i, el) => el.value).get();

        if (!confirm($button.text().trim() + ' voor ' + ids.length + ' afbeelding(en)?')) {
            return;
        }

        $('.bulk-action').prop('disabled', true);
        $.post($button.data('endpoint'), { action: $button.data('action'), ids: ids }, null, 'json')
            .done(function(data) {
                toonMelding(data.message, data.success ? 'success' : 'error');
                setTimeout(() => location.reload(), 1500);
            })
            .fail(function(xhr) {
                toonMelding(xhr.responseJSON?.message || 'Er is iets misgegaan, probeer het opnieuw.', 'error');
                updateBulkActions();
            });
    });
</script>
<?php
include '../common/footer.php';
?>
