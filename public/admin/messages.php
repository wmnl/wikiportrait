<?php
require_once '../common/bootstrap.php';
require_once '../common/header.php';
$session->checkAdmin();
include 'tabs.php';
?>
<div id="content">
    <div class="page-header">
        <h2><?= t('message.manage_title'); ?></h2>
        <a href="addmessage.php" class="button"><i class="fa-solid fa-plus fa-lg"></i><span><?=
            t('message.new'); ?></span></a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="id center">#</th>
                    <th><?= t('common.title'); ?></th>
                    <th><?= t('common.actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $results = DB::query("SELECT id, title FROM messages");
                $editLabel = t('common.edit');
                $deleteLabel = t('common.delete');
                foreach ($results as $row) {
                    $id = $row['id'];
                    $title = htmlspecialchars($row['title']);

                    echo "<tr>";
                    echo "<td data-title=\"&#xf02e;\" class=\"center\">$id</td>";
                    echo "<td data-title=\"&#xf02b;\">$title</td>";
                    echo "<td data-title=\"&#xf0ae;\"><a href=\"editmessage.php?id={$id}\">$editLabel</a><div ";
                    echo "class=\"divider\"></div><a href=\"#\" ";
                    echo "onclick=\"confirmDelete({$id})\");\">$deleteLabel</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
include '../common/footer.php';
?>