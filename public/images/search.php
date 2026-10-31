<?php
//@phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
require_once '../common/bootstrap.php';
$session->checkLogin();
require_once '../common/header.php';
include 'tabs.php';

$errors   = [];
$results  = [];
$query    = isset($_GET['q']) ? trim($_GET['q']) : '';
$searched = isset($_GET['q']);

if ($searched) {
    if ($query === '') {
        $errors[] = t('search.no_term');
    } else {
        $results = DB::query(
            "SELECT i.*, users.otrsname AS owneruser
             FROM images i
             LEFT JOIN users ON i.owner = users.id
             WHERE i.`key` = %s
                OR i.email LIKE %ss
                OR i.name LIKE %ss
                OR i.title LIKE %ss
             ORDER BY i.id DESC
             LIMIT 100",
            $query,
            $query,
            $query,
            $query
        );
    }
}
?>

<div id="content">
    <div class="page-header">
        <h2><?= t('search.title'); ?></h2>
    </div>

    <form method="get" class="search-form">
        <div class="input-container">
            <label for="search"><i class="fa-solid fa-search"></i><?= t('search.term'); ?></label>
            <input type="text" name="q" id="search" value="<?= htmlspecialchars($query); ?>"
                placeholder="<?= t('search.placeholder'); ?>" />
        </div>
        <div class="bottom right">
            <button class="green" type="submit">
                <i class="fa-solid fa-search"></i><?= t('search.button'); ?>
            </button>
        </div>
    </form>

    <?php if (!empty($errors)) : ?>
    <div class="box red">
        <ul>
            <?php foreach ($errors as $error) : ?>
            <li><?= htmlspecialchars($error); ?></li>
            <?php endforeach ?>
        </ul>
    </div>
    <?php elseif ($searched && empty($results)) : ?>
    <div class="box red"><?= t('search.no_results'); ?></div>
    <?php elseif (!empty($results)) : ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th><?= t('images.col_photo'); ?></th>
                    <th><?= t('common.title'); ?></th>
                    <th><?= t('images.col_uploader'); ?></th>
                    <th><?= t('search.col_email'); ?></th>
                    <th><?= t('images.col_date'); ?></th>
                    <th><?= t('images.col_owner'); ?></th>
                    <th><?= t('search.col_status'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $statuses = [
                    0 => t('search.status_submission'),
                    1 => t('images.archive'),
                    2 => t('images.unverified'),
                ];
                foreach ($results as $row) :
                    $id       = $row['id'];
                    $filename = htmlspecialchars($row['filename']);
                    $title    = htmlspecialchars($row['title']);
                    $name     = htmlspecialchars($row['name']);
                    $email    = htmlspecialchars($row['email']);
                    $owner    = empty($row['owneruser'])
                        ? t('images.unassigned')
                        : htmlspecialchars($row['owneruser']);
                    $status   = $statuses[$row['archived']] ?? t('search.status_unknown');
                    ?>
                <tr>
                    <td data-title="&#xf03e;" class="image">
                        <a href="single.php?id=<?= $id; ?>">
                            <img src="../uploads/thumbs/<?= $filename; ?>" />
                        </a>
                    </td>
                    <td data-title="&#xf02b;">
                        <a href="single.php?id=<?= $id; ?>"><?= $title; ?></a>
                    </td>
                    <td data-title="&#xf007;"><?= $name; ?></td>
                    <td data-title="&#xf0e0;"><?= $email; ?></td>
                    <td data-title="&#xf073;"><?= formatDate($row['timestamp']); ?></td>
                    <td data-title="&#xf0f0;"><?= $owner; ?></td>
                    <td data-title="&#xf024;"><?= $status; ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>

<?php include '../common/footer.php'; ?>