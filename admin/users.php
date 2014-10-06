<?php
	include '../header.php';
	include 'tabs.php';
	checkAdmin();
?>
<div id="content">

	<div class="page-header">

		<h2>Gebruikersbeheer</h2>

		<a href="adduser.php" class="button float-right"><i class="fa fa-plus-square fa-lg"></i><span>Nieuwe gebruiker</span></a>

		<div class="clear"></div>

	</div>

	<div class="box grey">Welkom bij het gebruikersbeheer. Kies een gebruiker of maak een nieuwe gebruiker aan.</div>

	<div class="table-container">

		 <table>
			 <thead>
				 <tr>
				 	<th class="id center">ID</th>
					<th>Gebruikersnaam</th>
					<th>OTRS-naam</th>
					<th>E-mailadres</th>
					<th class="icon">Beheerder</th>
					<th class="actions-1 center">Acties</th>
				 </tr>
			 </thead>
			 <tbody>
			 <?php
				 $query = "SELECT * FROM users";
				 $result = mysqli_query($connection, $query);
				 while ($row = mysqli_fetch_assoc($result))
				 {
						 $id = $row['id'];
						 $username = $row['username'];
						 $otrsname = $row['otrsname'];
						 $email = $row['email'];
						 $sysop = $row['isSysop'];

						 echo "<tr>";
						 	echo "<td data-title =\"ID\" class=\"center\">$id</td>";
							 echo "<td data-title =\"Gebruikersnaam\">$username</td>";
							 echo "<td data-title =\"OTRS-naam\">$otrsname</td>";
							 echo "<td data-title =\"E-mailadres\">$email</td>";
							 if ($sysop) echo "<td data-title =\"Beheerder\"><i class=\"fa fa-check-square fa-lg\" style=\"color:#339966;\"></i>Ja</td>"; else echo "<td data-title =\"Beheerder\"><i class=\"fa fa-minus-square fa-lg\" style=\"color:#990000;\"></i>Nee</td>";
							 echo "<td data-title =\"Acties\" class=\"center\"><a class=\"button\" href=\"edituser.php?id=$id\"><i class=\"fa fa-pencil\"></i>Bewerken</a></td>";
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
