<?php 
	ob_start();
	session_start();
	require 'config/connect.php';
	$basispad = "/wikiportret";
	
	function checkLogin(){
		$basispad = "/wikiportret";
		if (!isset($_SESSION['user']))
			header("Location:/wikiportret/login.php");
	}
	
	function checkAdmin(){
		$basispad = "/wikiportret";
		if (!isset($_SESSION['user']))
			header("Location:/wikiportret/login.php");
		elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == false)
			header("Location:/wikiportret");

	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="<?php echo $basispad ?>/style/style.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $basispad ?>/style/responsive.css" />
	   	<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	   	<script src="<?php echo $basispad ?>/scripts/jquery-1.11.1.min.js"></script>
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