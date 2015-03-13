<?php
    require 'config.php';

    ob_start();
    session_start();
    require 'connect.php';

    $basispad = BASE_URL;

    function checkLogin() {
	   global $basispad;

	   if (!isset($_SESSION['user'])) {
	       header("Location:$basispad/login.php");
       }
    }

    function checkAdmin() {
    	global $basispad;

    	if (!isset($_SESSION['user'])) {
    	    header("Location:$basispad/login.php");
        } elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == false) {
    	    header("Location:$basispad");
        }
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
	<link rel="apple-touch-icon" href="<?= $basispad ?>/apple-touch-icon.png" />

	<!-- Overige icons -->
	<link rel="shortcut icon" href="<?= $basispad ?>/favicon.ico" />
	<link rel="icon" type="image/x-icon" href="<?= $basispad ?>/favicon.ico" />
	<link rel="icon" type="image/png" href="<?= $basispad ?>/apple-touch-icon.png" />
	<meta name="msapplication-TileColor" content="#444444">
	<meta name="msapplication-TileImage" content="<?= $basispad ?>/apple-touch-icon.png">

	<link rel="stylesheet" href="<?= $basispad ?>/style/style.css" />
	<link rel="stylesheet" href="<?= $basispad ?>/style/responsive.css" />
    <link rel="stylesheet" href="<?= $basispad; ?>/lib/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= $basispad; ?>/lib/sweetalert/lib/sweet-alert.css">

    <script src="<?= $basispad; ?>/lib/jquery/dist/jquery.min.js"></script>
    <script src="<?= $basispad; ?>/lib/sweetalert/lib/sweet-alert.min.js"></script>

	<title>Wikiportret - Stel uw foto's ter beschikking</title>
    </head>
    <body>
	<div id="all">
	    <div id="header">
		<h1><a href="<?= $basispad; ?>/index.php">Wikiportret</a></h1>
	    </div>

	    <div class="menu">
	       <?php include 'menu.php'; ?>
	    </div>
