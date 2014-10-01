<?php
    function showquestion($text, $yes, $no, $exp=null)
    {
        echo "<p>$text</p>";
        if (!empty($exp)) echo "<div class=\"box grey\">$exp</div>";
        echo "<div class=\"bottom\"><a class=\"button red\" href=\"wizard.php?question=$no\"><i class=\"fa fa-thumbs-down fa-lg fa-fw\"></i>Nee</a><span class=\"divider\">&nbsp;</span><a class=\"button green\" href=\"wizard.php?question=$yes\"><i class=\"fa fa-thumbs-up fa-lg fa-fw\"></i>Ja</a></div>";
    }

    function result($result)
    {
        if ($result == "noupload")
        {
            echo "<h3>Het advies van de uploadwizard is:</h3>";
            echo "<div class=\"box red\">Niet uploaden</div>";
            echo "<div class=\"bottom\"><a class=\"button\" href=\"index.php\">Terug naar de startpagina</a></div>";
        }
        elseif ($result == "success")
        {
            echo "<h3>Uploaden voltooid</h3>";
            echo "<div class=\"box green\">De afbeelding is met succes geüpload.<br />Een vrijwilliger zal de afbeelding zo snel mogelijk beoordelen en contact met u opnemen.<br />Als u wilt, kunt u <a href='track.php?image=" . $_GET['id'] . "&key=" . sha1($_GET['id']) . "'>hier</a> uw inzending volgen.</div>";
            echo "<div class=\"bottom\"><a class=\"button\" href=\"upload.php\">Nog een afbeelding uploaden</a></div>";
        }
        else
        {
            echo "<h3>Het advies van de uploadwizard is:</h3>";
            echo "<div class=\"box green\">Upload de afbeelding onder de CC-BY-SA licentie</div>";
            echo "<div class=\"bottom\"><a class=\"button\" href=\"upload.php\"><i class=\"fa fa-cloud-upload fa-lg fa-fw\"></i>Uploaden</a></div>";
        }
    }
?>