<?php
	require '../connect.php';   
        include '../header.php';
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
    <h2>Gebruiker bewerken</h2>
    <?php					
            $query = "SELECT * FROM users WHERE id = $id";
            $result = mysql_query($query);
            if (mysql_num_rows($result) == 0)
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
                                            $query = "UPDATE users SET username = '$username', otrsname = '$otrsname', email = '$email', isSysop = $admin WHERE id = $id";
                                    }
                                    else
                                    {
                                            $query = "UPDATE users SET username = '$username', password =  '$password', otrsname = '$otrsname', email = '$email', isSysop = $admin WHERE id = $id";
                                    }
                                    mysql_query($query);
                                    header("Location: users.php");
                            }  

                            if (!empty($errors))
                            {
                                    echo "<div class=\"error\"><ul>";

                                    foreach ($errors as $error)
                                    {
                                            echo "<li>" . $error . "</li>";
                                    }

                                    echo "</ul></div>";
                            }
                    }


                    $row = mysql_fetch_assoc($result);
    ?>

    <form method="post">
            <div class="input-container">
                    <label for="username"><i class="fa fa-user fa-lg fa-fw"></i>Gebruikersnaam</label>
                    <input type="text" name="username" id="username" value="<?php echo $row['username']; ?>" />
            </div>

            <div class="input-container">
                    <label for="otrsname"><i class="fa fa-briefcase fa-lg fa-fw"></i>OTRS-naam</label>
                    <input type="text" name="otrsname" id="otrsname" value="<?php echo $row['otrsname']; ?>" />
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
                    <input type="submit" class="float-right" name="postback" value="Opslaan &rarr;" />
                    <a href="users.php" class="button float-right">&larr; Terug</a>
            </div>

    </form>
    <?php
            }
    ?>
</div>
<?php
    include '../footer.php';
?>