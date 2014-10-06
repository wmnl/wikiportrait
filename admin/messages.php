<?php
	include '../header.php';
	include 'tabs.php';
	checkAdmin();
?>
<div id="content">

	<div class="page-header">

		 <h2>Berichtenbeheer</h2>

		 <a href="addmessage.php" class="button float-right"><i class="fa fa-plus-square fa-lg"></i><span>Nieuw bericht</span></a>

	</div>

	<div class="box grey">Welkom bij het berichtenbeheer. Kies een bericht of voeg een nieuw bericht toe.</div>

	<div class="table-container">

		<table>
		<thead>
			<tr>
				<th class="id center">ID</th>
				<th>Titel</th>
				<th class="actions-2 center">Acties</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$query = "SELECT * FROM messages";
			$result = mysqli_query($connection, $query);
			while ($row = mysqli_fetch_assoc($result))
			{
				$id = $row['id'];
				$title = $row['title'];

				echo "<tr>";
					echo "<td data-title=\"ID\" class=\"center\">$id</td>";
					echo "<td data-title=\"Titel\">$title</td>";
					echo "<td data-title=\"Acties\" class=\"center\"><a class=\"button\" href=\"editmessage.php?id=$id\"><i class=\"fa fa-pencil\"></i>Bewerken</a><div class=\"divider\"></div><a class=\"button red\" href=\"deletemessage.php?nojs=1&id=$id\" onclick=\"return confirmDelete('$id')\"><i class=\"fa fa-trash-o\"></i>Verwijderen</a></td>";
				echo "</tr>";
			}
		?>
		</tbody>
		</table>
	</div>

</div>
<?php
	include '../footer.php';
?>
