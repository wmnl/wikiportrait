<?php
    header('HTTP/1.1 404 Not Found');
    header('Status: 404 Not Found');
    require 'common/bootstrap.php';
    require 'common/header.php';
?>
<div id="content">

    <h2>Oh nee, deze pagina bestaat niet!</h2>

    <p>Helaas, u bent terecht gekomen op een deel van Wikiportret dat eigenlijk niet bestaat. Het kan ook zijn dat de pagina wel bestaat, maar op dit moment niet beschikbaar is.</p>
    <p>Controleer of u een correcte URL in uw adresbalk heeft staan of keer terug naar de vorige pagina. Pak anders een stroopwafel en probeer het later opnieuw.</p>

</div>
<?php
    include 'common/footer.php';
?>
