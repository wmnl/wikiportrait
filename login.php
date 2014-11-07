<?php
	include 'header.php';
?>

<div id="content">

	<h2>Inloggen</h2>

	<p>Hier kunnen medewerkers van Wikiportret inloggen in het beheergedeelte.</p>

	<?php
		if (isset($_SESSION['user']))
		{
		    header("Location:index.php");
		}

		if (!empty($errors))
		{
			echo "<div class=\"box red\"><ul>";

			foreach ($errors as $error)
			{
				echo "<li>$error</li>";
			}

			echo "</ul></div>";
		}
		else
		{
			if (isset($_POST['postback']))
			{
				$errors = array();

			if (empty ($_POST['username']))
			{
				array_push($errors, "U heeft geen gebruikersnaam ingevuld");
			}

			if (empty ($_POST['password']))
			{
				array_push($errors, "U heeft geen wachtwoord ingevuld");
			}

			if (empty($errors))
			{
				$query = sprintf("SELECT * FROM users WHERE username = '%s' AND password = '%s' AND active = 1", mysqli_real_escape_string($connection, $_POST['username']), sha1($_POST['password']));
				$result = mysqli_query($connection, $query);

				if (mysqli_num_rows($result) == 1)
				{
					$row = mysqli_fetch_assoc($result);

					if ($row['isSysop'] == 1)
					{
						$isSysop = true;
					}
					else
					{
						$isSysop = false;
					}

					$_SESSION['user'] = $row['id'];
					if ($isSysop)
					{
						$_SESSION['isSysop'] = true;
					}
					else
					{
						$_SESSION['isSysop'] = false;
					}

					header("Location:index.php");
				}
				else
				{
					echo "<div class=\"box red\">Gebruikersnaam en/of wachtwoord incorrect</div>";
				}
			}
			else
			{
			echo "<div class=\"box red\"><ul>";

			foreach ($errors as $error)
			{
				echo "<li>$error</li>";
			}

			echo "</ul></div>";
			}
		}
	}
	?>

	<form method="post">

		<div class="input-container">
			<label for="username"><i class="fa fa-user fa-lg fa-fw"></i>Gebruikersnaam</label>
			<input type="text" name="username" id="username" autocorrect="off"/>
		</div>

		<div class="input-container">
			<label for="password"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord</label>
			<input type="password" name="password" id="password" />
		</div>

		<div class="bottom right">
			<button class="green" type="submit" name="postback"><i class="fa fa-sign-in fa-lg"></i>Inloggen</button>
		</div>

	</form>

</div>

<?php
	include 'footer.php';
?>
