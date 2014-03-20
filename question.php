<?php
    function showquestion($text, $yes, $no, $exp=null)
    {
        echo "\t\t\t\t<p>$text";
        if (!empty($exp)) echo " <a href=\"#\" class=\"tooltip\"><img src=\"./images/question.png\"><span>$exp</span></a></p>";
        echo "\n\t\t\t\t<a class=\"question\" href=\"upload.php?question=$yes\">Ja</a> <a class=\"question\" href=\"upload.php?question=$no\">Nee</a>";
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
        
        else
        {
            echo "<h3>Het advies van de uploadwizard is:</h3>";
            echo "<div id=\"good\">";
            echo "<p>Upload de afbeelding onder de CC-BY-SA licentie</p>";
            echo "<p><a class=\"uploadbutton\" href=\"uploadform.php\">Uploaden</a></p>";
            echo "</div>";               }
    }
?>
