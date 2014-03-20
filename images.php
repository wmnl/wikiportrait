<?php 
    include "connect.php";
    session_start();
    if (!isset($_SESSION['user']))
        header("Location:../login.php");
    else
    {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="style/style.css" />
        <title>Wikiportret - Stel uw foto's ter beschikking</title>
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
                <h2>Ingestuurde foto's</h2>
                
                <table border="1px">
                    <tr>
                        <th width="20%">Afbeelding</th>
                        <th width="80%">Titel</th>
                    </tr>
                    <?php
                        $query = "SELECT * FROM images"
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>