<?php
    require '../common/bootstrap.php';
    require '../common/header.php';
    $session->checkAdmin();
    include 'tabs.php';
?>
<div id="content">
    <div class="page-header">
	<h2>Gebruikersbeheer</h2>
	<a href="adduser.php" class="button"><i class="fa fa-plus fa-lg"></i><span>Nieuwe gebruiker</span></a>
    </div>

    <div class="table-container">
	<table>
	    <thead>
		<tr>
		    <th class="id center">#</th>
		    <th>Gebruikersnaam</th>
		    <th>OTRS-naam</th>
		    <th>E-mailadres</th>
		    <th>Beheerder</th>
		    <th>Acties</th>
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
			echo "<td data-title =\"&#xf0ae;\"><a href=\"edituser.php?id=$id\">Bewerken</td>";
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
