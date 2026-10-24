<?php
require_once '../common/bootstrap.php';
require_once '../common/header.php';
$session->checkAdmin();
include 'tabs.php';

$results = DB::query('SELECT * FROM users');
?>
<div id="content">
    <div class="page-header">
        <h2><?= t('user.manage_title'); ?></h2>
        <a href="adduser.php" class="button">
            <i class="fa-solid fa-plus fa-lg"></i><span><?= t('user.new'); ?></span>
        </a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="id center">#</th>
                    <th><?= t('user.username'); ?></th>
                    <th><?= t('user.vrts_name'); ?></th>
                    <th><?= t('user.email'); ?></th>
                    <th><?= t('user.admin'); ?></th>
                    <th><?= t('common.actions'); ?></th>
                    <th><?= t('user.active'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row) : ?>
                <tr>
                    <td data-title="&#xf02e;" class="center"><?= $row['id']; ?></td>
                    <td data-title="&#xf007;"><?= htmlspecialchars($row['username']); ?></td>
                    <td data-title="&#xf0b1;"><?= htmlspecialchars($row['otrsname']); ?></td>
                    <td data-title="&#xf0e0;"><?= htmlspecialchars($row['email']); ?></td>
                    <td data-title="&#xf0f0;"><?= $row['isSysop'] ? t('common.yes') : t('common.no'); ?></td>
                    <td data-title="&#xf0ae;">
                        <a href="edituser.php?id=<?= $row['id']; ?>"><?= t('common.edit'); ?></a>
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