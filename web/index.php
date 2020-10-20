<?php
    require 'common/bootstrap.php';
    require 'common/header.php';

?>
    <div id="content">
<?php
if (!CLOSED) {
    ?>
        <h2>Welkom op Wikiportret!</h2>
        <p>Staat er op Wikipedia een artikel zonder portretfoto? En heeft u een foto die bij een artikel zou passen?
        Stel dan uw foto hier ter beschikking en de vrijwilligers van Wikipedia doen de rest.</p>
    <p>Als u veel foto's heeft, of bijvoorbeeld foto's met andere onderwerpen dan beroemdheden overweegt u dan
        uw foto's onder te brengen bij Wikimedia Commons, de centrale mediadatabank van Wikipedia.</p>

    <div class="bottom center">
        <a href="wizard.php" class="button green bigbutton"><i class="fa fa-cloud-upload fa-lg"
                                                               ></i>Een portretfoto insturen</a>
    </div>
<?php } else { ?>
        <h2>Welkom op Wikiportret!</h2>
        <p>Helaas is het momenteel niet mogelijk om foto's te uploaden.
            Het is niet bekend wanneer Wikiportret weer beschikbaar is.
            U kunt in de tussentijd uw foto rechtstreeks uploaden naar
            <a href="https://commons.wikimedia.org/wiki/Special:UploadWizard?uselang=nl"
               target="_blank" rel="noopener nofollow">Wikimedia Commons</a></p>
        <p>Onze excuses voor het ongemak<br>Team Wikiportret</p>
<?php } ?>
    </div>
    <?php
    include 'common/footer.php';
    ?>