<?php
require_once '../common/bootstrap.php';
require_once '../common/schemafunctions.php';
$session->checkAdmin();

if (empty($_SESSION['schema_token'])) {
    $_SESSION['schema_token'] = bin2hex(random_bytes(16));
}

$validToken = hash_equals($_SESSION['schema_token'], $_POST['token'] ?? '');
$error      = false;

// An uploaded scheme.sql is kept in the session, so the selected differences can be applied afterwards
if ($validToken && isset($_POST['upload'])) {
    $upload = $_FILES['schema'] ?? null;
    $sql    = $upload && $upload['error'] === UPLOAD_ERR_OK ? file_get_contents($upload['tmp_name']) : '';

    if (parseCreateTables($sql)) {
        $_SESSION['schema_upload'] = ['sql' => $sql, 'name' => $upload['name'], 'time' => time()];
    } else {
        $error = 'Het bestand bevat geen CREATE TABLE-statements.';
    }
}

if ($validToken && isset($_POST['forget'])) {
    unset($_SESSION['schema_upload']);
}

$file = schemaFile();
if (isset($_SESSION['schema_upload'])) {
    $source = 'geüpload bestand ' . $_SESSION['schema_upload']['name']
        . ' (' . date('d-m-Y H:i', $_SESSION['schema_upload']['time']) . ')';
    $wanted = parseCreateTables($_SESSION['schema_upload']['sql']);
} elseif ($file) {
    $source = $file;
    $wanted = parseCreateTables(file_get_contents($file));
} else {
    $source = null;
    $wanted = [];
}

$results = [];
if ($source && $validToken && isset($_POST['apply']) && is_array($_POST['diffs'] ?? null)) {
    $results = applySchemaDifferences($wanted, $_POST['diffs']);
}

$diffs = $source ? schemaDifferences($wanted, currentTables()) : [];

require_once '../common/header.php';
include 'tabs.php';
?>
<div id="content">
    <div class="page-header">
        <h2>Database</h2>
    </div>

    <?php if ($error) : ?>
    <div class="box red"><?= $error; ?></div>
    <?php endif ?>

    <form method="post" enctype="multipart/form-data" class="schema-upload">
        <input type="hidden" name="token" value="<?= $_SESSION['schema_token']; ?>">
        <input type="file" name="schema" accept=".sql" required>
        <button type="submit" name="upload"><i class="fa-solid fa-upload fa-lg"></i>scheme.sql uploaden</button>
    </form>

    <?php if (!$source) : ?>
    <div class="box blue">Upload etc/scheme.sql om de database ermee te vergelijken.</div>
    <?php else : ?>
        <?php foreach ($results as $result) : ?>
        <div class="box <?= $result['success'] ? 'green' : 'red'; ?>">
            <?= htmlspecialchars($result['message']); ?>:
            <code><?= htmlspecialchars($result['sql']); ?></code>
        </div>
        <?php endforeach ?>

        <div class="schema-source">
            Vergeleken met <code><?= htmlspecialchars($source); ?></code>.
            <?php if (isset($_SESSION['schema_upload'])) : ?>
            <form method="post" class="schema-forget">
                <input type="hidden" name="token" value="<?= $_SESSION['schema_token']; ?>">
                <button type="submit" name="forget">Geüpload bestand niet meer gebruiken</button>
            </form>
            <?php endif ?>
        </div>

        <?php if (!$diffs) : ?>
        <div class="box green">De database komt overeen met scheme.sql.</div>
        <?php else : ?>
        <form method="post" onsubmit="return confirm('Geselecteerde wijzigingen doorvoeren in de database?');">
            <input type="hidden" name="token" value="<?= $_SESSION['schema_token']; ?>">
            <div class="table-container">
                <table class="schema">
                    <thead>
                        <tr>
                            <th class="select-container"></th>
                            <th class="schema-table">Tabel</th>
                            <th>Verschil</th>
                            <th>SQL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($diffs as $diff) : ?>
                        <tr>
                            <td data-title="&#xf14a;" class="select">
                                <?php if ($diff['sql'] !== null) : ?>
                                <input type="checkbox" name="diffs[]" value="<?= htmlspecialchars($diff['id']); ?>">
                                <?php endif ?>
                            </td>
                            <td data-title="&#xf1c0;"><?= htmlspecialchars($diff['table']); ?></td>
                            <td data-title="&#xf05a;">
                                <?= htmlspecialchars($diff['description']); ?>
                                <?php if ($diff['removes']) : ?>
                                <br><span class="alert">Verwijdert dit uit de database</span>
                                <?php endif ?>
                                <?php if ($diff['now'] !== null) : ?>
                                <br>Nu: <code><?= htmlspecialchars($diff['now']); ?></code>
                                <?php endif ?>
                            </td>
                            <td data-title="&#xf121;">
                                <?php if ($diff['sql'] !== null) : ?>
                                <pre class="schema-sql"><?= htmlspecialchars($diff['sql']); ?></pre>
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="bottom right">
                <button class="green" type="submit" name="apply">
                    <i class="fa-solid fa-database fa-lg"></i>Geselecteerde wijzigingen doorvoeren
                </button>
            </div>
        </form>
        <?php endif ?>
    <?php endif ?>
</div>

<?php include '../common/footer.php'; ?>
