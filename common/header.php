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

    <script>
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-8050986-7']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
</head>
<body>
	<div id="all">
	    <div id="header">
		<h1><a href="<?= $basispad; ?>/index.php">Wikiportret</a></h1>
	    </div>

	    <div class="menu">
	       <?php include 'menu.php'; ?>
	    </div>
