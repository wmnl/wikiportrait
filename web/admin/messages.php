<?php
    require '../common/bootstrap.php';
    require '../common/header.php';
    $session->checkAdmin();
    include 'tabs.php';
?>
<div id="content">
    <div class="page-header">
    <h2>Berichtenbeheer</h2>
    <a href="addmessage.php" class="button"><i class="fa fa-plus fa-lg"></i><span>Nieuw bericht</span></a>
    </div>

    <div class="table-container">
    <table>
        <thead>
        <tr>
            <th class="id center">#</th>
            <th>Titel</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $results = DB::query("SELECT id, title FROM messages");
        foreach ($results as $row) {
            $id = $row['id'];
            $title = htmlspecialchars($row['title']);

            echo "<tr>";
            echo "<td data-title=\"&#xf02e;\" class=\"center\">$id</td>";
            echo "<td data-title=\"&#xf02b;\">$title</td>";
            echo "<td data-title=\"&#xf0ae;\"><a href=\"editmessage.php?id=$id\">Bewerken</a><div class=\"divider\">"
            . "</div><a href=\"#\" onclick=\"confirmDelete($id)\");\">Verwijderen</a></td>";
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
