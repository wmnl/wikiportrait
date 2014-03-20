<?php 
    session_start();
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
                <h2>Welkom op Wikiportret!</h2>
                <p> Staat er op Wikipedia een artikel zonder portretfoto? En heeft u een foto die bij een artikel zou passen? Stel dan uw foto hier ter beschikking en de vrijwilligers van Wikipedia doen de rest.</p>

                <p>Wikimedia Commons Als u veel foto's heeft, of bijvoorbeeld foto's met andere onderwerpen dan beroemdheden overweegt u dan uw foto's onder te brengen bij Wikimedia Commons, de centrale mediadatabank van Wikipedia. </p>
            </div>
        </div>
    </body>
</html>
