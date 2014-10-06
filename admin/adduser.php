<?php
	include '../header.php';
	include 'tabs.php';
	checkAdmin();
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
			if (mysqli_num_rows(mysqli_query($connection, $query)))
				array_push($errors, "Deze gebruikersnaam bestaat al");
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
			$query = sprintf("INSERT INTO users(username, password, otrsname, email, isSysop, active)
						VALUES('%s', '%s', '%s', '%s', %d, %d)", mysqli_real_escape_string($connection, $username), mysqli_real_escape_string($connection, sha1($password)), mysqli_real_escape_string($connection, $otrsname), mysqli_real_escape_string($connection, $email), $admin, 1);

			mysqli_query($connection, $query);
			header("Location:users.php");
		}
	}
?>
<div id="content">

	<div class="page-header">

		<h2>Gebruiker toevoegen</h2>

		<a href="users.php" class="button red float-right"><i class="fa fa-ban fa-lg"></i><span>Annuleren</span></a>

	</div>

	<?php
		if (!empty($errors))
		{
			echo "<div class=\"box red\"><ul>";

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
			<input type="text" name="otrsname" id="otrsname" <?php if (isset($_POST['otrsname'])) echo $_POST['otrsname'] ?> />
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
			<input type="email" name="email" id="email" <?php if (isset($_POST['email'])) echo $_POST['email'] ?> />
		</div>

		<div class="input-container">
			<label for="admin"><i class="fa fa-user-md fa-lg fa-fw"></i>Beheerder</label>
			<div class="checkbox">
					<input type="checkbox" name="admin" id="admin" /><label for="admin">Ja</label>
			</div>
		</div>

		<div class="bottom right">
				<button class="green" type="submit" name="postback"><i class="fa fa-plus-square fa-lg"></i>Toevoegen</button>
		</div>
	</form>
</div>
<?php
	include '../footer.php';
?>
