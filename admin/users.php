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
				 	<th class="center" style="width:3em;">ID</th>
					 <th>Gebruikersnaam</th>
					 <th>OTRS-naam</th>
					 <th>E-mailadres</th>
					 <th class="icon center">Beheerder</th>
					 <th class="icon center">Bewerken</th>
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
							 if ($sysop) echo "<td data-title =\"Beheerder\" class=\"center\"><i class=\"fa fa-check-square fa-lg\" style=\"color:#339966;\"></i></td>"; else echo "<td data-title =\"Beheerder\" class=\"center\"><i class=\"fa fa-minus-square fa-lg\" style=\"color:#990000;\"></i></td>";
							 echo "<td data-title =\"Bewerken\" class=\"center\"><a href=\"edituser.php?id=$id\"><i class=\"fa fa-pencil fa-lg\"></i></a></td>";
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