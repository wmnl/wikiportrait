<?php
	include '../header.php';
	include 'tabs.php';
	checkAdmin();
?>			
	<div id="content">
		<h2>Berichtenbeheer</h2>
		
		<a href="addmessage.php" class="button blue float-right"><i class="fa fa-plus-square fa-lg"></i>Nieuw bericht</a>
		<div class="box grey">Welkom bij het berichtenbeheer. Kies een bericht of voeg een nieuw bericht toe.</div>
		
		<table>
			<thead>
				<tr>
					<th>Titel</th>
					<th>Bericht</th>
					<th class="center" style="width:6em;" colspan="2">Acties</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$query = "SELECT * FROM messages";
				$result = mysql_query($query);
				while ($row = mysql_fetch_assoc($result))
				{
					$id = $row['id'];
					$title = $row['title'];
					$message = $row['message'];

					echo "<tr>";
						echo "<td>$title</td>";
						echo "<td><div style=\"height:200px; overflow-y:scroll; -webkit-overflow-scrolling:touch;\">" . str_replace("\n", "<br />", $message) . "</div></td>";
						echo "<td class=\"center\"><a href=\"editmessage.php?id=$id\"><i class=\"fa fa-pencil fa-lg\"></i></a></td>";
						echo "<td class=\"center\"><a href=\"deletemessage.php?id=$id\"><i class=\"fa fa-trash-o fa-lg\"></i></a></td>";
					echo "</tr>";
				}
			?>
			</tbody>
		</table>
	</div>
<?php
	include '../footer.php';
?>
