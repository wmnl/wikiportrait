<?php
require 'common/bootstrap.php';

if (isset($_POST['postback'])) {
    checkUpload();
}

require 'common/header.php';
if (!CLOSED) {

    ?>
    <script>
    function comparenames() {
        var depicted = document.getElementById('title').value;
        var photographer = document.getElementById('source').value;

        if (depicted.length > 0 && photographer.length > 0 &&
                (depicted.toLowerCase() == photographer.toLowerCase())) {
                    swal('U hebt aangegeven dat de persoon op de foto dezelfde persoon is als de auteursrechthebbende',
                            'Let erop dat alleen de fotograaf toestemming kan geven om de foto vrij te geven '
                            + 'onder een vrije licentie.',
                            'warning');
                }
    }
    $(document).ready(function () {
        $(document).on('change', 'input[name="source_self"]', function () {
            if ("No" === $(this).val()) {
                $('#source_container').removeClass('hidden');
                    } else {
                        $('#source').removeAttr('required');
                        $('#source_container').addClass('hidden');
                    }
                });
    })
</script>
<div id="content">
    <?php
    if (hasvalidationerrors()) {
        showvalidationsummary();
    }

    ?>

    <h2>Uploadformulier</h2>

    <form method="post" enctype="multipart/form-data">

        <div class="input-container">
            <label for="file"><i aria-hidden="true" class="fa fa-file-image-o fa-lg fa-fw"></i>Kies een bestand</label>
            <div class="file">
                <input type="file" name="file" id="file" required="required" accept="image/*" />
            </div>
        </div>

        <div class="input-container">
            <label for="title"><i aria-hidden="true" class="fa fa-eye fa-lg fa-fw"></i>Wie staat er op de foto?</label>
            <input type="text" name="title" id="title" required="required" value="<?php
            if (!empty($_POST['title'])) {
                echo $_POST['title'];
            }

            ?>" onblur="comparenames()" />
        </div>

        <div class="input-container">
            <label for="source"><i aria-hidden="true" class="fa fa-camera fa-lg fa-fw"
                                   ></i>Heeft u deze foto zelfgemaakt?</label>
            <div class="radiogroup">
                <input type="radio" name="source_self" value="Yes" id="source_self_yes" required="required" <?php
                echo
                (array_key_exists('source_self', $_POST) && $_POST['source_self'] == 'Yes') ? 'checked="checked"' :
                    '';

                ?>>Ja, ik heb deze foto zelf gemaakt<br>
                <input type="radio" name="source_self" value="No" id="source_self_no" required="required"  <?php
                echo
                (array_key_exists('source_self', $_POST) && $_POST['source_self'] == 'No') ? 'checked="checked"' :
                    '';

                ?>>Nee, ik heb deze foto niet zelf gemaakt<br>
                <input type="radio" name="source_self" value="Unknown" id="source_self_unknown"
                       required="required"  <?php
                        echo (array_key_exists('source_self', $_POST) && $_POST['source_self'] == 'Unknown') ?
                        'checked="checked"' : '';

                        ?>>Ik weet niet wie de foto gemaakt heeft
            </div>
        </div>

        <div class="input-container hidden" id="source_container">
            <label for="source"><i aria-hidden="true" class="fa fa-camera-retro fa-lg fa-fw"
                                   ></i>Wat is de naam van de fotograaf?</label>
            <input type="text" name="source" id="source" <?php
            echo (array_key_exists('source_self', $_POST) &&
            $_POST['source_self'] != 'Yes') ? 'required="required"' : '';

            ?> value="<?php
if (!empty($_POST['source'])) {
    echo $_POST['source'];
}

?>" onblur="comparenames()" />
        </div>

        <div class="input-container">
            <label for="name"><i aria-hidden="true" class="fa fa-user fa-lg fa-fw"></i>Uw naam</label>
            <input type="text" name="name" id="name" required="required" value="<?php
            if (!empty($_POST['name'])) {
                echo $_POST['name'];
            }

            ?>" />
        </div>

        <div class="input-container">
            <label for="email"><i aria-hidden="true" class="fa fa-envelope fa-lg fa-fw"></i>Uw e-mailadres</label>
            <input type="email" id="email" name="email" required="required" value="<?php
            if (!empty($_POST['email'])) {
                echo $_POST['email'];
            }

            ?>" />
        </div>

        <div class="input-container">
            <label for="date"><i aria-hidden="true" class="fa fa-calendar fa-lg fa-fw"
                                 ></i>Wanneer is de foto genomen? <span class="optional">(optioneel)</span></label>
            <input type="text" name="date" id="date" value="<?php
            if (!empty($_POST['date'])) {
                echo $_POST['date'];
            }

            ?>" />
        </div>

        <div class="input-container">
            <label for="description"><i aria-hidden="true" class="fa fa-comment fa-lg fa-fw"></i>Omschrijving <span
                    class="optional">(optioneel)</span></label>
            <textarea name="description" id="description"><?php
            if (!empty($_POST['description'])) {
                echo $_POST['description'];
            }

            ?></textarea>
        </div>

        <div class="input-container">
            <label><i aria-hidden="true" class="fa fa-bars fa-lg fa-fw"></i> Licentievoorwaarden</label>
            <?php
            $licentie = "Door het uploaden van dit materiaal en het klikken op de knop 'Upload foto' verklaart "
                . "u dat u de rechthebbende eigenaar bent van het materiaal. Door dit materiaal te uploaden geeft "
                . "u toestemming voor het gebruik van het materiaal onder de condities van de door u geselecteerde "
                . "licentie(s), deze condities variëren per licentie maar houden in ieder geval in dat het materiaal "
                . "verspreid, bewerkt en commercieel gebruikt mag worden door eenieder. Voor de specifieke extra "
                . "condities per licentie verwijzen wij u naar de bijbehorende licentieteksten. Na het vrijgeven "
                . "kunt u niet meer terugkomen op deze rechten. De Wikimedia Foundation en haar chapters (waaronder "
                . "de Vereniging Wikimedia Nederland) zijn op geen enkele wijze aansprakelijk voor misbruik van het "
                . "materiaal of aanspraak op het materiaal door derden. De eventuele geportretteerden hebben geen "
                . "bezwaar tegen publicatie onder genoemde licenties. Ook uw eventuele opdrachtgever geeft "
                . "toestemming.";

            ?>
            <textarea disabled="disabled"><?= $licentie; ?></textarea>
        </div>
            <?php //phpcs:disable Generic.Files.LineLength.TooLong ?>
            <div class="input-container">
            <label for="terms"><i aria-hidden="true" class="fa fa-gavel fa-lg fa-fw"></i>Toestemming</label>
            <div class="checkbox">
                    <input type="checkbox" name="terms" id="terms" /><label for="terms">Ja, ik ga akkoord met de bovenstaande licentievoorwaarden, de <a href="<?= $basispad ?>/privacyverklaring.php" target="_blank">privacyverklaring</a> en het opslaan van mijn IP-adres.</label><br />
                    <input type="checkbox" name="euvs" id="euvs" /><label for="euvs">Ja, ik geef toestemming voor het opslaan van mijn gegevens op de servers van de Wikimedia Foundation in de Verenigde Staten, waarbij de gegevens niet worden doorgegeven op grond van het EU-US Privacy Shield of de wettelijke uitzondering die is opgenomen in artikel 77 van de Wet bescherming persoonsgegevens (Wbp). Bij doorgifte buiten de Europese Unie bestaat er een kans dat het beschermingsniveau minder hoog zal zijn dan binnen de EU.</label>
                </div>
        </div>
            <?php //phpcs:enable Generic.Files.LineLength.TooLong ?>
            <div class="bottom right">
            <input type='hidden' name='postback'></input>
            <button class="green" type='button' onclick='disableButton(this)'><i aria-hidden="true"
                                                                                 class="fa fa-cloud-upload fa-lg"
                                                                                 ></i>Upload foto</button>
        </div>

    </form>
</div>
<?php } else { ?>
    <div id="content">
        <h2>Welkom op Wikiportret!</h2>
    <p>Helaas is het momenteel niet mogelijk om foto's te uploaden.
        Het is niet bekend wanneer Wikiportret weer beschikbaar is.
        U kunt in de tussentijd uw foto rechtstreeks uploaden naar
        <a href="https://commons.wikimedia.org/wiki/Special:UploadWizard?uselang=nl"
           target="_blank" rel="noopener nofollow">Wikimedia Commons</a></p>
           <p>Onze excuses voor het ongemak<br>Team Wikiportret</p>
    </div>
<?php } ?>
<?php
            include 'common/footer.php';

?>
