<?php
    ob_start();
    require "connect.php";   
    session_start();

    if (isset($_POST['postback']))
    {
        $errors = array();

        if (empty ($_POST['username']))
                array_push ($errors, "Je hebt geen gebruikersnaam ingevuld");
        if (empty ($_POST['password']))
                array_push ($errors, "Je hebt geen wachtwoord ingevuld");

        if (empty($errors))
        {

            $query = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "' AND password = '" . sha1($_POST['password']) . "'";
            echo $query;
            $result = mysql_query($query);

            if (mysql_num_rows($result) == 1)
            {
                $row = mysql_fetch_assoc($result);
                if ($row['isSysop'] == 1) 
                        $isSysop = true;
                else 
                        $isSysop = false;

                $_SESSION['user'] = $row['id'];
                if ($isSysop)
                        $_SESSION['isSysop'] = true;
                else
                        $_SESSION['isSysop'] = false;


                header("Location:index.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" type="text/css" href="style/style.css" />
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<title>Wikiportret - Inloggen</title>
	</head>
	<body>
		<div id="all">
			<div id="header">
				<h1>Wikiportret</h1>
			</div>
			
			<div id="menu">
				<?php include 'menu.php'; ?>
			</div>
			
			<div id="content">
				<h2>Inloggen</h2>
				<p>Hier kunnen medewerkers van Wikiportret inloggen in het beheergedeelte.</p>
				
				<?php
					if (!empty($errors))
					{
						echo "<div class=\"error\"><ul>";
						
						foreach ($errors as $error)
						{
							echo "<li>$error</li>";
						}
						
						echo "</ul></div>";
					}
				?>
				
				<form method="post">
					<div class="input-container">
						<label for="username"><i class="fa fa-user fa-lg fa-fw"></i>Gebruikersnaam</label>
						<input type="text" name="username" id="username" />
					</div>
					
					<div class="input-container">
						<label for="password"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord</label>
						<input type="password" name="password" id="password" />
					</div>
					
					<div class="input-container bottom">
						<input type="submit" class="float-right" name="postback" value="Inloggen &rarr;" />
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
