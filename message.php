<?php 
    session_start();
    require "connect.php";
    
    if (!isset($_GET['file']) || !isset($_GET['message']))
    {
        header("Location:images.php");
    }
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
                <h2>Kant-en-klaar berichtje versturen</h2>
                
                <?php
                    
                ?>
                <pre><?php echo $row['message']; ?></pre>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>