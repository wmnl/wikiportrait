<?php
    session_start();
    include "../connect.php";
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
        <link rel="stylesheet" type="text/css" href="../style/style.css" />
        <title>Wikiportret - Stel uw foto's ter beschikking</title>
        <style>
            table tr td{ text-align: center; }
        </style>
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
                <ul>
                    <li><a href="adduser.php">Gebruiker toevoegen</a></li>
                </ul>
                
                <table width="95%" border="1px solid black">
                    <tr>
                        <th>Gebruikersnaam</th>
                        <th>OTRS-naam</th>
                        <th>E-mailadres</th>
                        <th>Admin</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    
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
                                if ($sysop) echo "<td><img src=\"../images/accept.png\" width=\"20px\" />"; else echo "<td><img src=\"../images/delete.png\" width=\"20px\" />";
                                echo "<td><a href=\"edituser.php?id=$id\"><img src=\"../images/modify.png\" title=\"Bewerken\" width=\"20px\" /></a></td>";
                                echo "<td><a href=\"deleteuser.php?id=$id\"><img src=\"../images/delete.png\" title=\"Verwijderen\" width=\"20px\" /></a></td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>

<?php 
    }
?>

