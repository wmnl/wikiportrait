<?php
    function showquestion($text, $yes, $no, $exp=null)
    {
        echo "<p>$text";
        if (!empty($exp)) echo " <a href=\"#\" class=\"tooltip\"><img src=\"./images/question.png\"><span>$exp</span></a>";
        echo "</p>";
        echo "\n\t\t\t\t<p><a class=\"question\" href=\"wizard.php?question=$yes\">Ja</a> <a class=\"question\" href=\"wizard.php?question=$no\">Nee</a></p>";
    }
    
    function result($result)
    {
        if ($result == "noupload")
        {
            echo "<h3>Het advies van de uploadwizard is:</h3>";
            echo "<div id=\"bad\">";
            echo "<p>Niet uploaden</p>";
            echo "<p><a class=\"backbutton\" href=\"index.php\">Terug naar de startpagina</a></p>";
            echo "</div>";           
        }
        elseif ($result == "success")
        {
            echo "<h3>Uploaden voltooid</h3>";
            echo "<div id=\"good\">";
            echo "<p>De afbeelding is met succes geüpload.</p>";
            echo "<p>Een vrijwilliger zal de afbeelding zo snel mogelijk beoordelen en contact met u opnemen.</p>";
            echo "<p><a class=\"uploadbutton\" href=\"upload.php\">Nog een afbeelding uploaden</a></p>";
            echo "</div>";
        }
        else
        {
            echo "<h3>Het advies van de uploadwizard is:</h3>";
            echo "<div id=\"good\">";
            echo "<p>Upload de afbeelding onder de CC-BY-SA licentie</p>";
            echo "<p><a class=\"uploadbutton\" href=\"upload.php\">Uploaden</a></p>";
            echo "</div>";               }
    }
?>
