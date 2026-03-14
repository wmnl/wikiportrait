<?php
require_once '../common/bootstrap.php';
require_once '../common/header.php';
$session->checkAdmin();
include 'tabs.php';

$results = DB::query('SELECT * FROM users');
?>
<div id="content">
    <div class="page-header">
        <h2>Gebruikersbeheer</h2>
        <a href="adduser.php" class="button">
            <i class="fa-solid fa-plus fa-lg"></i><span>Nieuwe gebruiker</span>
        </a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="id center">#</th>
                    <th>Gebruikersnaam</th>
                    <th>VRTS-naam</th>
                    <th>E-mailadres</th>
                    <th>Beheerder</th>
                    <th>Acties</th>
                    <th>Actief</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row) : ?>
                <tr>
                    <td data-title="&#xf02e;" class="center"><?= $row['id']; ?></td>
                    <td data-title="&#xf007;"><?= htmlspecialchars($row['username']); ?></td>
                    <td data-title="&#xf0b1;"><?= htmlspecialchars($row['otrsname']); ?></td>
                    <td data-title="&#xf0e0;"><?= htmlspecialchars($row['email']); ?></td>
                    <td data-title="&#xf0f0;"><?= $row['isSysop'] ? 'Ja' : 'Nee'; ?></td>
                    <td data-title="&#xf0ae;">
                        <a href="edituser.php?id=<?= $row['id']; ?>">Bewerken</a>
                    </td>
                    <td data-title="&#xf235;">
                        <i class="fa-solid <?= $row['active'] ? 'fa-check' : 'fa-times'; ?>"
                            style="color: <?= $row['active'] ? 'green' : 'red'; ?>"></i>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../common/footer.php'; ?>