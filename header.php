<?php 
    ob_start();
    session_start();
    require 'connect.php';
    $basispad = "/wikiportret";
    
    function checkLogin(){
        if (!isset($_SESSION['user']))
            header("Location:$basispad/login.php");
    }
    
    function checkAdmin(){
        if (!isset($_SESSION['user']))
            header("Location:$basispad/login.php");
        elseif (isset($_SESSION['user']) && $_SESSION['isSysop'] == false)
            header("Location:" . $basispad);

    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" type="text/css" href="<?php echo $basispad ?>/style/style.css" />
       	<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
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