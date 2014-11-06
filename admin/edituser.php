<?php
	include '../header.php';
	include 'tabs.php';
	checkAdmin();
        if (isset($_GET['id']))
        {
            $id = $_GET['id'];
        }
        else 
        {
            header("Location: users.php");
        }
?>			
<div id="content">

	<div class="page-header">

		<h2>Gebruiker bewerken</h2>

		<a href="users.php" class="button red"><i class="fa fa-ban fa-lg"></i><span>Annuleren</span></a>

	</div>
	
    <?php					
            $query = sprintf("SELECT * FROM users WHERE id = %d", mysqli_real_escape_string($connection, $id)); 
            $result = mysqli_query($connection, $query);
            if (mysqli_num_rows($result) == 0)
            {
                echo "Gebruiker niet gevonden!";
            }
            else
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

                    if (isset($_POST['active']))
                    {
                        $active = 1;
                    }
                    else
                    {
                        $active = 0;
                    }

                    if (empty($username))
                    {
                        array_push($errors, "Er is geen gebruikersnaam ingevuld");
                    }

                    if (empty($otrsname))
                    {
                        array_push($errors, "Er is geen OTRS-naam ingevuld");
                    }

                    if (isset($password))
                    {
                        if($password != $password2)
                        {
                            array_push($errors, "De twee ingevulde wachtwoorden komen niet met elkaar overeen");
                        }
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
                        if (empty($_POST['password']))
                        {
                            $query = sprintf("UPDATE users SET username = '%s', otrsname = '%s', email = '%s', isSysop = %d, active = %d WHERE id = %d", mysqli_real_escape_string($connection, $username), mysqli_real_escape_string($connection, $otrsname), mysqli_real_escape_string($connection, $email), $admin, $active, $id);
                        }
                        else
                        {
                            $query = sprintf("UPDATE users SET username = '%s', password =  '%s', otrsname = '%s', email = '%s', isSysop = %d, active = %d WHERE id = %d", mysqli_real_escape_string($connection, $username), mysqli_real_escape_string($connection, $password), mysqli_real_escape_string($connection, $otrsname), mysqli_real_escape_string($connection, $email), $admin, $active, $id);
                        }
                        mysqli_query($connection, $query);
                        header("Location: users.php");
                    }  

                    if (!empty($errors))
                    {
                        echo "<div class=\"box red\"><ul>";

                        foreach ($errors as $error)
                        {
                            echo "<li>" . $error . "</li>";
                        }

                        echo "</ul></div>";
                    }
                }


                $row = mysqli_fetch_assoc($result);
    ?>

    <form method="post">
            <div class="input-container">
                <label for="username"><i class="fa fa-user fa-lg fa-fw"></i>Gebruikersnaam</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($row['username']); ?>" />
            </div>

            <div class="input-container">
                <label for="otrsname"><i class="fa fa-briefcase fa-lg fa-fw"></i>OTRS-naam</label>
                <input type="text" name="otrsname" id="otrsname" value="<?php echo htmlspecialchars($row['otrsname']); ?>" />
            </div>

            <div class="input-container">
                <label for="password"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord</label>
                <input type="password" name="password" id="password" placeholder="Enkel invullen als je het wachtwoord wilt wijzigen"/>
            </div>

            <div class="input-container">
                <label for="password2"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord nogmaals</label>
                <input type="password" name="password2" id="password2" placeholder="Enkel invullen als je het wachtwoord wilt wijzigen" />
            </div>

            <div class="input-container">
                <label for="email"><i class="fa fa-envelope fa-lg fa-fw"></i>E-mailadres</label>
                <input type="email" name="email" id="email" value="<?php echo $row['email']; ?>"/>
            </div>

            <div class="input-container">
                <label for="admin"><i class="fa fa-user-md fa-lg fa-fw"></i>Beheerder</label>
                <div class="checkbox">
                    <input type="checkbox" name="admin" id="admin" <?php if ($row['isSysop'] == 1) echo "checked"; ?> /><label for="admin">Ja</label>
                </div>
            </div>
        
            <div class="input-container">
                <label for="active"><i class="fa fa-power-off fa-lg fa-fw"></i>Geactiveerd</label>
                <div class="checkbox">
                    <input type="checkbox" name="active" id="active" <?php if ($row['active'] == 1) echo "checked"; ?> /><label for="active">Ja</label>
                </div>
            </div>

            <div class="bottom right">
					<button class="green" name="postback"><i class="fa fa-floppy-o fa-lg"></i>Opslaan</button>
			</div>

    </form>
    <?php
            }
    ?>
</div>
<?php
    include '../footer.php';
?>
