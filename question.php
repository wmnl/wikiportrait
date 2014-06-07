<?php
    function showquestion($text, $yes, $no, $exp=null)
    {
        echo "<p>$text</p>";
        if (!empty($exp)) echo "<div class=\"succes\" style=\"display:block;\">$exp</div>";
        echo "\n\t\t\t\t<div class=\"bottom\"><a class=\"button\" href=\"wizard.php?question=$yes\"><i class=\"fa fa-thumbs-up fa-lg fa-fw\"></i>Ja</a><span class=\"divider\">&nbsp;</span><a class=\"button\" href=\"wizard.php?question=$no\"><i class=\"fa fa-thumbs-down fa-lg fa-fw\"></i>Nee</a></div>";
    }

    function result($result)
    {
        if ($result == "noupload")
        {
            echo "<h3>Het advies van de uploadwizard is:</h3>";
            echo "<div class=\"error\">Niet uploaden</div>";
            echo "<a class=\"button back\" href=\"index.php\">Terug naar de startpagina</a>";
        }
        elseif ($result == "success")
        {
            echo "<h3>Uploaden voltooid</h3>";
            echo "<div class=\"succes\">De afbeelding is met succes geüpload.<br />Een vrijwilliger zal de afbeelding zo snel mogelijk beoordelen en contact met u opnemen.</div>";
            echo "<a class=\"button upload\" href=\"upload.php\">Nog een afbeelding uploaden</a>";
        }
        else
        {
            echo "<h3>Het advies van de uploadwizard is:</h3>";
            echo "<div class=\"succes\">Upload de afbeelding onder de CC-BY-SA licentie</div>";
            echo "<a class=\"button upload\" href=\"upload.php\"><i class=\"fa fa-cloud-upload fa-lg fa-fw\"></i>Uploaden</a>";
        }
    }
?>
