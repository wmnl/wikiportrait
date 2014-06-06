<?php
	session_start();
	require "../connect.php";
	if (!isset($_SESSION["user"]))
		header("Location:../login.php");
	elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == false)
	{
		header("Location:../");
	}
	elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == true)
	{
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" type="text/css" href="../style/style.css" />
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<title>Wikiportret - Stel uw foto's ter beschikking</title>
	</head>
	<body>
		<div id="all">
			<div id="header">
				<h1>Wikiportret</h1>
			</div>
			
			<div id="menu">
			   <?php include '../menu.php'; ?>
			</div>
			
			<div id="content">
				
				<h2>Gebruikersbeheer</h2>
							
				<a href="adduser.php" class="button float-right"><i class="fa fa-plus fa-lg fa-fw"></i>Nieuwe gebruiker</a>
				<div class="succes">Welkom bij het gebruikersbeheer. Kies een gebruiker of maak een nieuwe gebruiker aan.</div>
				
				<table>
					<thead>
						<tr>
							<th>Gebruikersnaam</th>
							<th>OTRS-naam</th>
							<th>E-mailadres</th>
							<th>Beheerder</th>
							<th>Acties</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$query = "SELECT * FROM users";
						$result = mysql_query($query);
						while ($row = mysql_fetch_assoc($result))
						{
							$id = $row['id'];
							$username = $row['username'];
							$otrsname = $row['otrsname'];
							$email = $row['email'];
							$sysop = $row['isSysop'];
							
							echo "<tr>";
								echo "<td>$username</td>";
								echo "<td>$otrsname</td>";
								echo "<td>$email</td>";
								if ($sysop) echo "<td><i class=\"fa fa-check-square-o fa-lg\"></i></td>"; else echo "<td><i class=\"fa fa-minus-square-o fa-lg\"></i></td>";
								echo "<td><a href=\"edituser.php?id=$id\"><i class=\"fa fa-wrench fa-lg\"></i></a><a href=\"deleteuser.php?id=$id\"><i class=\"fa fa-trash-o fa-lg\"></i></a></td>";
							echo "</tr>";
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>

<?php 
	}
?>

