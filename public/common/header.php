<!DOCTYPE html>
<html lang="<?= currentLanguage(); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width" />

    <link rel="stylesheet" href="<?= $basispad; ?>/style/style.css" />
    <link rel="stylesheet" href="<?= $basispad; ?>/style/responsive.css" />
    <link rel="stylesheet" href="<?= $basispad; ?>/lib/font-awesome/css/all.min.css">
    <script defer src="<?= $basispad; ?>/lib/font-awesome/js/fontawesome.js"></script>
    <script src="<?= $basispad; ?>/lib/jquery/dist/jquery.min.js"></script>

    <title><?= htmlspecialchars(t('header.title')); ?></title>
    <meta name="description" content="<?= htmlspecialchars(t('header.description')); ?>" />
    <script>
    var i18nMessages = <?= json_encode(jsTranslations(), JSON_HEX_TAG | JSON_UNESCAPED_UNICODE); ?>;
    function i18n(key) {
        return i18nMessages.hasOwnProperty(key) ? i18nMessages[key] : key;
    }
    </script>
    <?php if (strpos($_SERVER['SERVER_NAME'], "wikiportret.nl") !== false) { ?>
    <script>
    // Set to the same value as the web property used on the site
    var gaProperty = 'UA-8050986-7';
    // Disable tracking if the opt-out cookie exists.
    var disableStr = 'ga-disable-' + gaProperty;
    if (document.cookie.indexOf(disableStr + '=true') > -1) {
        window[disableStr] = true;
    }
    // Opt-out function
    function gaOptout() {
        document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
        window[disableStr] = true;
    }
    </script>
    <script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-8050986-7', 'auto');
    ga('set', 'forceSSl', true);
    ga('set', 'anonymizeIp', true);
    ga('send', 'pageview');
    </script>
    <?php } ?>
</head>

<body>
    <div id="all">
        <div id="header">
            <h1><a href="<?= $basispad; ?>/index.php">Wikiportret</a></h1>
        </div>

        <div class="menu">
            <?php include 'menu.php'; ?>
        </div>