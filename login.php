<?php
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
        <link rel="stylesheet" type="text/css" href="style/style.css" />
        <title>Wikiportret - Inloggen</title>
    </head>
    <body>
        <div id="all">
            <div id="header">
                <h1>Wikiportret - Inloggen</h1>
            </div>
            
            <div id="menu">
                <?php include 'menu.php'; ?>
            </div>
            
            <div id="content">
                <p>Hier kunnen medewerkers van Wikiportret inloggen in het beheergedeelte.</p>
                
                <?php
                    if (!empty($errors))
                    {
                        echo "<ul>";
                        
                        foreach ($errors as $error)
                        {
                            echo "<li>$error</li>";
                        }
                        
                        echo "</ul>";
                    }
                ?>
                
                <form method="post">
                    <p>
                        <label for="username">Gebruikersnaam: </label>
                        <input type="text" name="username" id="username" />
                    </p>
                    
                    <p>
                        <label for="password">Wachtwoord: </label>
                        <input type="password" name="password" id="password" />
                    </p>
                    
                    <p>
                        <label>&nbsp;</label>
                        <input type="submit" name="postback" />
                    </p>
                </form>
            </div>
        </div>
    </body>
</html>
