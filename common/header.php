<?php
    ob_start();
    session_start();
    require 'connect.php';
    
    $basispad = "/wikiportret";

    function checkLogin(){
	global $basispad;
	if (!isset($_SESSION['user']))
	    header("Location:/wikiportret/login.php");
    }

    function checkAdmin(){
	global $basispad;
	if (!isset($_SESSION['user']))
	    header("Location:/wikiportret/login.php");
	elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == false)
	    header("Location:/wikiportret");
    }

    $cookieLifetime = 365 * 24 * 60 * 60;
    setcookie(session_name(),session_id(),time()+$cookieLifetime);
?>
<!DOCTYPE html>
<html>
    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    <meta name="viewport" content="initial-scale=1.0" />
	    <meta name="mobile-web-app-capable" content="yes" />
	    <meta name="application-name" content="Wikiportret" />
	    <!-- Apple -->
	    <meta name="apple-mobile-web-app-capable" content="yes" />
	    <meta name="apple-mobile-web-app-title" content="Wikiportret" />
	    <link rel="apple-touch-icon" href="<?php echo $basispad ?>/apple-touch-icon.png" />
	    <!-- Overige icons -->
	    <link rel="shortcut icon" href="<?php echo $basispad ?>/favicon.ico" />
	    <link rel="icon" type="image/x-icon" href="<?php echo $basispad ?>/favicon.ico" />
	    <link rel="icon" type="image/png" href="<?php echo $basispad ?>/apple-touch-icon.png" />
	    <meta name="msapplication-TileColor" content="#444444">
	    <meta name="msapplication-TileImage" content="<?php echo $basispad ?>/apple-touch-icon.png">
	    <link rel="stylesheet" type="text/css" href="<?php echo $basispad ?>/style/style.css" />
	    <link rel="stylesheet" type="text/css" href="<?php echo $basispad ?>/style/responsive.css" />
	    <link href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
	    <script src="<?php echo $basispad ?>/scripts/jquery-2.1.1.min.js"></script>
	    <title>Wikiportret - Stel uw foto's ter beschikking</title>
    </head>
    <body>
	    <div id="all">
		    <div id="header">
			    <h1><a href="<?php echo $basispad; ?>/index.php">Wikiportret</a></h1>
		    </div>

		    <div class="menu">
		       <?php include 'menu.php'; ?>
		    </div>
