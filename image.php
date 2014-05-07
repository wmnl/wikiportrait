<?php 
    include "connect.php";
    session_start();
    if (!isset($_SESSION['user']))
        header("Location:login.php");
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
                <?php
                    if (!isset($_GET['id']))
                        echo "Er is geen ID opgegeven!";
                    else
                    {
                        $query = "SELECT * FROM images WHERE id = " . $_GET['id'];
                        $result = mysql_query($query);
                        
                        if (mysql_num_rows($result) == 0)
                        {
                            echo "Foto niet gevonden!";
                        }
                        else
                        {
                            $row = mysql_fetch_assoc($result);
                ?>
                <h3><?php echo $row['title']; ?></h3>
                
                <a href="uploads/<?php echo $row['filename']; ?>" target="_blank" ><img class="detail" src="uploads/<?php echo $row['filename'] ;?>"/></a>
                
                <ul>
                    <li>Titel: <?php echo $row['title']; ?></li>
                    <li>Auteur: <?php echo $row['source']; ?></li>
                    <li>Naam uploader: <?php echo $row['name']; ?></li>
                    <li>IP uploader: <?php echo $row['ip']; ?></li>
                    <li>Geüpload op: <?php echo gmdate("d F Y H:i:s", $row['timestamp']) ?></li>
                    <li>Beschrijving: <?php echo $row['description'];?></li>
                </ul>
                
                <?php
                        }
                    }
                ?>
                
            </div>
        </div>
    </body>
</html>
<?php
    }
?>