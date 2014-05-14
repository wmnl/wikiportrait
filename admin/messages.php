<?php
    session_start();
    require "../connect.php";
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
                <h2>Berichtenbeheer</h2>
                <ul>
                    <li><a href="adduser.php">Bericht  toevoegen</a></li>
                </ul>
                
                <table width="95%" border="1px solid black">
                    <tr>
                        <th width="15%">Titel</th>
                        <th width="85%">Bericht</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    
                    <?php
                        $query = "SELECT * FROM messages";
                        $result = mysql_query($query);
                        while ($row = mysql_fetch_assoc($result))
                        {
                            $id = $row['id'];
                            $title = $row['title'];
                            $message = $row['message'];
                            
                            echo "<tr>";
                                echo "<td>$title</td>";
                                echo "<td>" . str_replace("\n", "<br />", $message) . "</td>";
                                echo "<td><a href=\"editmessage.php?id=$id\"><img src=\"../images/modify.png\" title=\"Bewerken\" width=\"20px\" /></a></td>";
                                echo "<td><a href=\"deletemessage.php?id=$id\"><img src=\"../images/delete.png\" title=\"Verwijderen\" width=\"20px\" /></a></td>";
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

