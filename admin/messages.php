<?php
	include '../header.php';
	include 'tabs.php';
	checkAdmin();
?>			
<div id="content">

	<div class="page-header">

		 <h2>Berichtenbeheer</h2>
	
		 <a href="addmessage.php" class="button float-right"><i class="fa fa-plus-square fa-lg"></i><span>Nieuw bericht</span></a>
		 
		 <div class="clear"></div>
		 
	</div>
	
	<div class="box grey">Welkom bij het berichtenbeheer. Kies een bericht of voeg een nieuw bericht toe.</div>

	<div class="table-container">

		<table>
		<thead>
			<tr>
				<th>Titel</th>
				<th>Bericht</th>
				<th class="icon center" colspan="2">Acties</th>
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
				$message = $row['message'];
		
				echo "<tr>";
					  echo "<td data-title =\"Titel\">$title</td>";
					  echo "<td data-title =\"Bericht\"><div style=\"height:200px; overflow-y:scroll; -webkit-overflow-scrolling:touch;\">" . str_replace("\n", "<br />", $message) . "</div></td>";
					  echo "<td data-title =\"Bewerken\" class=\"center\"><a href=\"editmessage.php?id=$id\"><i class=\"fa fa-pencil fa-lg\"></i></a></td>";
					  echo "<td data-title =\"Verwijderen\" class=\"center\"><a href=\"deletemessage.php?nojs=1&id=$id\" onclick=\"return confirmDelete('$id')\"><i class=\"fa fa-trash-o fa-lg\"></i></a></td>";
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