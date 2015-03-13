<?php
    require '../common/bootstrap.php';
    $session->checkAdmin();
    require '../common/header.php';

    include 'tabs.php';
?>
<div id="content">
    <div class="page-header">
	<h2>Berichtenbeheer</h2>
	<a href="addmessage.php" class="button"><i class="fa fa-plus-square fa-lg"></i><span>Nieuw bericht</span></a>
    </div>

    <div class="table-container">
	<table>
	    <thead>
		<tr>
		    <th class="id center">ID</th>
		    <th>Titel</th>
		    <th class="actions-2">Acties</th>
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
		    echo "<td data-title=\"&#xf0ae;\" class=\"center\"><a class=\"button\" href=\"editmessage.php?id=$id\"><i class=\"fa fa-pencil\"></i>Bewerken</a><div class=\"divider\"></div><a class=\"button red\" href=\"#\" onclick=\"confirmDelete($id)\");\"><i class=\"fa fa-trash-o\"></i>Verwijderen</a></td>";
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