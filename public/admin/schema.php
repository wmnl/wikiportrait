<?php
require_once '../common/bootstrap.php';
require_once '../common/schemafunctions.php';
$session->checkAdmin();

if (empty($_SESSION['schema_token'])) {
    $_SESSION['schema_token'] = bin2hex(random_bytes(16));
}

$file    = schemaFile();
$wanted  = $file ? parseCreateTables(file_get_contents($file)) : [];
$results = [];

if (
    $file && isset($_POST['apply'])
    && hash_equals($_SESSION['schema_token'], $_POST['token'] ?? '')
    && is_array($_POST['diffs'] ?? null)
) {
    $results = applySchemaDifferences($wanted, $_POST['diffs']);
}

$diffs = $file ? schemaDifferences($wanted, currentTables()) : [];

require_once '../common/header.php';
include 'tabs.php';
?>
<div id="content">
    <div class="page-header">
        <h2>Database</h2>
    </div>

    <?php if (!$file) : ?>
    <div class="box red">etc/scheme.sql is niet gevonden. Stel eventueel SCHEMA_FILE in config.php in.</div>
    <?php else : ?>
        <?php foreach ($results as $result) : ?>
        <div class="box <?= $result['success'] ? 'green' : 'red'; ?>">
            <?= htmlspecialchars($result['message']); ?>:
            <code><?= htmlspecialchars($result['sql']); ?></code>
        </div>
        <?php endforeach ?>

        <p>Vergeleken met <code><?= htmlspecialchars($file); ?></code>.</p>

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
