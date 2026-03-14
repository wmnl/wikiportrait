<?php
//@phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
require_once '../common/bootstrap.php';
$session->checkLogin();
require_once '../common/header.php';
include 'tabs.php';

$errors  = [];
$results = [];

if (isset($_POST['postback'])) {
    if (empty($_POST['search'])) {
        $errors[] = "Er is geen zoekterm ingevoerd";
    } else {
        $results = DB::query(
            "SELECT i.*, users.otrsname AS owneruser
             FROM images i
             INNER JOIN users ON i.owner = users.id
             WHERE title LIKE '%%%s%%'",
            $_POST['search']
        );
    }
}
?>

<div id="content">
    <div class="page-header">
        <h2>Zoeken</h2>
    </div>

    <form method="post" class="search-form">
        <div class="input-container">
            <label for="search"><i class="fa-solid fa-search"></i>Zoekterm</label>
            <input type="text" name="search" id="search" />
        </div>
        <div class="bottom right">
            <button class="green" type="submit" name="postback">
                <i class="fa-solid fa-search"></i>Zoeken
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
    <?php elseif (isset($_POST['postback']) && empty($results)) : ?>
    <div class="box red">Er zijn geen resultaten gevonden.</div>
    <?php elseif (!empty($results)) : ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Titel</th>
                    <th>Uploader</th>
                    <th>Datum</th>
                    <th>Eigenaar</th>
                    <th class="actions-1">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dateFormatter = new IntlDateFormatter(
                    'nl_NL',
                    IntlDateFormatter::NONE,
                    IntlDateFormatter::NONE,
                    null,
                    null,
                    "d MMMM yyyy"
                );
                foreach ($results as $row) :
                    $id       = $row['id'];
                    $filename = htmlspecialchars($row['filename']);
                    $title    = htmlspecialchars($row['title']);
                    $name     = htmlspecialchars($row['name']);
                    $owner    = htmlspecialchars($row['owneruser']);
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
                    <td data-title="&#xf073;"><?= $dateFormatter->format($row['timestamp']); ?></td>
                    <td data-title="&#xf0f0;"><?= $owner; ?></td>
                    <td data-title="&#xf0ae;" class="center">
                        <a class="button" href="single.php?id=<?= $id; ?>">
                            <i class="fa-solid fa-info"></i>Details
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>

<?php include '../common/footer.php'; ?>