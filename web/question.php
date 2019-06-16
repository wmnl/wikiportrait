<?php
    function showquestion($text, $yes, $no, $exp=null) {
        echo "<p>$text</p>";
        if (!empty($exp)) echo "<div class=\"box grey\">$exp</div>";

        echo "<div class=\"bottom center\"><a class=\"button\" href=\"wizard.php?question=$no\">Nee</a><span class=\"divider\">&nbsp;</span><a class=\"button\" href=\"wizard.php?question=$yes\">Ja</a></div>";
    }

    function result($result) {
        global $session;

        if ($result == "noupload") {
            echo "<h3>Het advies van de uploadwizard is:</h3>";
            echo "<div class=\"box red\">Niet uploaden</div>";
            echo "<div class=\"bottom right\"><a class=\"button\" href=\"index.php\"><i class=\"fa fa-home fa-lg\"></i>Terug naar de startpagina</a></div>";
        } elseif ($result == "success") {
            $key = $session->getLastUploadKey();
            echo "<h3>Uploaden voltooid</h3>";
            echo "<div class=\"box green\">De afbeelding is met succes geüpload.<br />Een vrijwilliger zal de afbeelding zo snel mogelijk beoordelen en contact met u opnemen.<br />Als u wilt, kunt u <a href=\"track.php?key=$key\">hier</a> uw inzending volgen.</div>";
            echo "<div class=\"bottom right\"><a class=\"button\" href=\"upload.php\"><i class=\"fa fa-cloud-upload fa-lg\"></i>Nog een afbeelding uploaden</a></div>";
        } elseif ($result == "verificatie") {
            $email = $session->getLastUploadEmail();
            echo "<h3>Email verificatie vereist</h3>";
            echo "<div class=\"box blue\">Wij hebben een email naar <strong>$email</strong> gestuurd voor verificatie.<br />Na succesvolle verificatie, is uw upload voltooid.<br /></div>";
        } elseif ($result == "duplicate") {
            echo "<h3>Uploaden is niet gelukt</h3>";
            echo "<div class=\"box blue\">".DUPLICATE_ERROR."<br /></div>";
        } elseif ($result == "failupload") {
            ?>
            <h3>Uploaden is niet gelukt</h3>
            <div class="box red">Helaas, het is niet gelukt deze afbeelding te uploaden. Wellicht zijn er technische problemen. Probeer het later nog eens.</div>
            <?php
        } elseif ($result == "fail1" || $result == "fail2") {
            ?>
            <h3>Uploaden is niet gelukt</h3>
            <div class="box red">Helaas, het is niet gelukt deze afbeelding te uploaden. Wellicht zijn er technische problemen. Probeer het later nog eens.</div>
            <?php
        } else {
            echo "<h3>Het advies van de uploadwizard is:</h3>";
            echo "<div class=\"box green\">Upload de afbeelding onder de <a href=\"https://creativecommons.org/licenses/by-sa/4.0/deed.nl\" target=\"_blank\">CC-BY-SA 4.0</a> licentie</div>";
            echo "<div class=\"bottom right\"><a class=\"button\" href=\"upload.php\"><i class=\"fa fa-cloud-upload fa-lg\"></i>Uploaden</a></div>";
        }
    }
?>
