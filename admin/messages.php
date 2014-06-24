<?php
	include '../header.php';
	include 'tabs.php';
	checkAdmin();
?>			
	<div id="content" style="border-top-left-radius:0px;">
		<h2>Berichtenbeheer</h2>
		
		<a href="adduser.php" class="button float-right"><i class="fa fa-plus-square fa-lg"></i>Bericht toevoegen</a>
		<div class="succes">Welkom bij het berichtenbeheer. Kies een bericht of voeg een nieuw bericht toe.</div>
		
		<table>
			<thead>
				<tr>
					<th>Titel</th>
					<th>Bericht</th>
					<th style="width:6em;">Acties</th>
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
						echo "<td style=\"padding:0px;\"><div style=\"height:200px; padding:10px; overflow:scroll;\">" . str_replace("\n", "<br />", $message) . "</div></td>";
						echo "<td><a href=\"editmessage.php?id=$id\"><i class=\"fa fa-wrench fa-lg\"></i></a> <a href=\"deletemessage.php?id=$id\"><i class=\"fa fa-trash-o fa-lg\"></i></a></td>";
					echo "</tr>";
				}
			?>
			</tbody>
		</table>
	</div>
<?php
	include '../footer.php';
?>
