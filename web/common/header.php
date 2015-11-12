<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width" />

    <link rel="stylesheet" href="<?= $basispad ?>/style/style.css" />
    <link rel="stylesheet" href="<?= $basispad ?>/style/responsive.css" />
    <link rel="stylesheet" href="<?= $basispad; ?>/lib/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= $basispad; ?>/lib/sweetalert/lib/sweet-alert.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

<!--    <script src="--><?//= $basispad; ?><!--/lib/jquery/dist/jquery.min.js"></script>
-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <script src="<?= $basispad; ?>/lib/sweetalert/lib/sweet-alert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

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
