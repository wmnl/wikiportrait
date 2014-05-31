<?php
    session_start();
    require "../connect.php";   
    if (!isset($_SESSION['user']))
        header("Location:../login.php");
    elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == false)
    {
        header("Location:../");
    }
    elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == true)
    {
        if (isset($_GET['id']))
        {
            $id = $_GET['id'];
        }
        else 
        {
            header("Location: users.php");
        }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../style/style.css" />
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
                                echo "<ul>";

                                foreach ($errors as $error)
                                {
                                    echo "<li>" . $error . "</li>";
                                }

                                echo "</ul>";
                            }
                        }
                        
                        
                        $row = mysql_fetch_assoc($result);
                ?>
                
                <form method="post">
                    <p>
                        <label for="username">Gebruikersnaam:</label>
                        <input type="text" name="username" id="username" value="<?php echo $row['username']; ?>" />
                    </p>
                    
                    <p>
                        <label for="otrsname">OTRS-naam:</label>
                        <input type="text" name="otrsname" id="otrsname" value="<?php echo $row['otrsname']; ?>" />
                    </p>
                    
                    <p>
                        <label for="password">Wachtwoord:</label>
                        <input type="password" name="password" id="password" placeholder="Enkel invullen als je het wachtwoord wilt wijzigen."/>
                    </p>
                    
                    <p>
                        <label for="password2">Wachtwoord nogmaals:</label>
                        <input type="password" name="password2" id="password2" placeholder="Enkel invullen als je het wachtwoord wilt wijzigen." />
                    </p>
                    
                    <p>
                        <label for="email">E-mailadres:</label>
                        <input type="email" name="email" id="email" value="<?php echo $row['email']; ?>"/>
                    </p>
                    
                    <p>
                        <label for="admin">Beheerder:</label>
                        <input type="checkbox" name="admin" id="admin" <?php if ($row['isSysop'] == 1) echo "checked"; ?> />
                    </p>
                    
                    <p>
                        <label>&nbsp;</label>
                        <input type="submit" name="postback" value="Opslaan" />
                    </p>
                    
                </form>
                <?php
                    }
                ?>
            </div>
        </div>
    </body>
</html>

<?php 
    }
?>

