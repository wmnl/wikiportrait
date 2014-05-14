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
                    if (!empty($errors))
                    {
                        echo "<ul>";
                        
                        foreach ($errors as $error)
                        {
                            echo "<li>" . $error . "</li>";
                        }
                        
                        echo "</ul>";
                    }
                ?>
                
                <form method="post">
                    <p>
                        <label for="username">Gebruikersnaam:</label>
                        <input type="text" name="username" id="username" />
                    </p>
                    
                    <p>
                        <label for="otrsname">OTRS-naam:</label>
                        <input type="text" name="otrsname" id="otrsname" />
                    </p>
                    
                    <p>
                        <label for="password">Wachtwoord:</label>
                        <input type="password" name="password" id="password" />
                    </p>
                    
                    <p>
                        <label for="password2">Wachtwoord nogmaals:</label>
                        <input type="password" name="password2" id="password2" />
                    </p>
                    
                    <p>
                        <label for="email">E-mailadres:</label>
                        <input type="email" name="email" id="email" />
                    </p>
                    
                    <p>
                        <label for="admin">Beheerder:</label>
                        <input type="checkbox" name="admin" id="admin" />
                    </p>
                    
                    <p>
                        <label>&nbsp;</label>
                        <input type="submit" value="Opslaan" />
                    </p>
                    
                </form>
                
            </div>
        </div>
    </body>
</html>

<?php 
    }
?>

