<?php
	session_start();
	ob_start();
	require "../connect.php";   
	if (!isset($_SESSION['user']))
		header("Location:../login.php");
	elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == false)
	{
		header("Location:../");
	}
	elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == true)
	{
		if (isset($_POST['postback']))
		{
			$errors = array();

			$username = $_POST['username'];
			$otrsname = $_POST['otrsname'];
			$password = $_POST['password'];
			$password2 = $_POST['password'];
			$email = $_POST['email'];
			if (isset($_POST['admin']))
			{
				$admin = 1;
			}
			else
			{
				$admin = 0;
			}

			if (empty($username))
			{
				array_push($errors, "Er is geen gebruikersnaam ingevuld");
			}
			else
			{
				$query = "SELECT * FROM users WHERE username = '$username'";
				if (mysql_num_rows(mysql_query($query)))
					array_push($errors, "Deze gebruikersnaam bestaat al oetlul");
			}

			if (empty($otrsname))
			{
				array_push($errors, "Er is geen OTRS-naam ingevuld");
			}

			if (empty($password))
			{
				array_push($errors, "Er is geen wachtwoord ingevuld");
			}
			elseif($password != $password2)
			{
				array_push($errors, "De twee ingevulde wachtwoorden komen niet met elkaar overeen");
			}

			if (empty($email))
			{
				array_push($errors, "Er is geen e-mailadres ingevuld");
			}
			elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				array_push($errors, "Er is geen geldig e-mailadres ingevuld");
			}
			
			if (count($errors) == 0)
			{
				$query = "INSERT INTO users(username, password, otrsname, email, isSysop)
						  VALUES('$username', '" . sha1($password) . "', '$otrsname', '$email', $admin)";
				
				mysql_query($query);
				header("Location:users.php");
			}
		}
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
				<h2>Gebruiker toevoegen</h2>
				<?php
					if (!empty($errors))
					{
						echo "<div class=\"error\"><ul>";
						
						foreach ($errors as $error)
						{
							echo "<li>" . $error . "</li>";
						}
						
						echo "</ul></div>";
					}
				?>
				
				<form method="post">
					<div class="input-container">
						<label for="username"><i class="fa fa-user fa-lg fa-fw"></i>Gebruikersnaam</label>
						<input type="text" name="username" id="username" value="<?php if (isset($_POST['username'])) echo $_POST['username'] ?>"/>
					</div>
					
					<div class="input-container">
						<label for="otrsname"><i class="fa fa-briefcase fa-lg fa-fw"></i>OTRS-naam</label>
						<input type="text" name="otrsname" id="otrsname" />
					</div>
					
					<div class="input-container">
						<label for="password"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord</label>
						<input type="password" name="password" id="password" />
					</div>
					
					<div class="input-container">
						<label for="password2"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord nogmaals</label>
						<input type="password" name="password2" id="password2" />
					</div>
					
					<div class="input-container">
						<label for="email"><i class="fa fa-envelope fa-lg fa-fw"></i>E-mailadres</label>
						<input type="email" name="email" id="email" />
					</div>
					
					<div class="input-container">
						<label for="admin"><i class="fa fa-user-md fa-lg fa-fw"></i>Beheerder</label>
						<div class="checkbox">
							<input type="checkbox" name="admin" id="admin" /><label for="admin">Ja</label>
						</div>
					</div>
					
					<div class="input-container">
						<input type="submit" class="float-right" name="postback" value="Opslaan &rarr;" />
						<a href="users.php" class="button float-right">&larr; Terug</a>
					</div>
					
				</form>
				
			</div>
		</div>
	</body>
</html>

<?php 
	}
?>

