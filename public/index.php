<?php
require_once 'common/bootstrap.php';
require_once 'common/header.php';
//@phpcs:disable Generic.Files.LineLength.TooLong
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
        <a href="wizard.php" class="button green bigbutton"><i class="fa-solid fa-cloud-upload fa-lg"></i>Een
            portretfoto insturen</a>
    </div>
    <?php } else { ?>
    <h2>Welkom op Wikiportret!</h2>
    <p>Helaas is het momenteel niet mogelijk om foto's te uploaden.
        Het is niet bekend wanneer Wikiportret weer beschikbaar is.
        U kunt in de tussentijd uw foto rechtstreeks uploaden naar
        <a href="https://commons.wikimedia.org/wiki/Special:UploadWizard?uselang=nl" target="_blank"
            rel="noopener nofollow">Wikimedia Commons</a>
    </p>
    <p>Onze excuses voor het ongemak<br>Team Wikiportret</p>
    <?php } ?>

    <div class="oss-banner" id="ossBanner">
        <button class="oss-banner__close" id="ossBannerClose" aria-label="Sluiten">&times;</button>
        <svg class="oss-banner__icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
            fill="currentColor" aria-hidden="true">
            <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
        </svg>
        <span class="oss-banner__text">
            <strong>Wikiportret is vrije software</strong> &mdash; net als Wikipedia is deze website gebouwd
            op <a href="https://www.gnu.org/licenses/gpl-2.0.html" target="_blank" rel="noopener noreferrer">open-source
                software (GPL-2.0)</a> die door vrijwilligers wordt ontwikkeld. Wilt u meehelpen de software te
            verbeteren? <a href="https://github.com/wmnl/wikiportrait" target="_blank" rel="noopener noreferrer">Bekijk
                de broncode op GitHub</a> of
            <a href="https://phabricator.wikimedia.org/tag/wikiportrait/" target="_blank" rel="noopener noreferrer">meld
                een idee of probleem</a>.
        </span>
    </div>

</div>
<?php
include 'common/footer.php';
?>