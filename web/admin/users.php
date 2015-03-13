<?php
    require '../common/bootstrap.php';
    $session->checkAdmin();
    require '../common/header.php';

    include 'tabs.php';
?>
<div id="content">
    <div class="page-header">
	<h2>Gebruikersbeheer</h2>
	<a href="adduser.php" class="button"><i class="fa fa-plus-square fa-lg"></i><span>Nieuwe gebruiker</span></a>
    </div>

    <div class="table-container">
	<table>
	    <thead>
		<tr>
		    <th class="id center">ID</th>
		    <th>Gebruikersnaam</th>
		    <th>OTRS-naam</th>
		    <th>E-mailadres</th>
		    <th>Beheerder</th>
		    <th class="actions-1">Acties</th>
		</tr>
	    </thead>
	    <tbody>
	    <?php
		$results = DB::query('SELECT * FROM users');
		foreach ($results as $row)
		{
		    $id = $row['id'];
		    $username = htmlspecialchars($row['username']);
		    $otrsname = htmlspecialchars($row['otrsname']);
		    $email = $row['email'];
		    $sysop = $row['isSysop'];

		    echo "<tr>";
			echo "<td data-title =\"&#xf02e;\" class=\"center\">$id</td>";
			echo "<td data-title =\"&#xf007;\">$username</td>";
			echo "<td data-title =\"&#xf0b1;\">$otrsname</td>";
			echo "<td data-title =\"&#xf0e0;\">$email</td>";
			if ($sysop) echo "<td data-title =\"&#xf0f0;\">Ja</td>"; else echo "<td data-title =\"&#xf0f0;\">Nee</td>";
			echo "<td data-title =\"&#xf0ae;\" class=\"center\"><a class=\"button\" href=\"edituser.php?id=$id\"><i class=\"fa fa-pencil\"></i>Bewerken</a></td>";
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
