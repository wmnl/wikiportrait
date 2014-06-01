<?php
	function showquestion($text, $yes, $no, $exp=null)
	{
		echo "<p>$text";
		if (!empty($exp)) echo " <a href=\"#\" class=\"tooltip\"><img src=\"./images/question.png\"><span>$exp</span></a>";
		echo "</p>";
		echo "\n\t\t\t\t<div class=\"wizardfooter\"><a class=\"button question\" href=\"wizard.php?question=$yes\">Ja</a> <a class=\"button question\" href=\"wizard.php?question=$no\">Nee</a></div>";
	}
	
	function result($result)
	{
		if ($result == "noupload")
		{
			echo "<h3>Het advies van de uploadwizard is:</h3>";
			echo "<div class=\"error\">";
			echo "<p>Niet uploaden</p>";
			echo "<div class=\"wizardfooter\"><a class=\"button back\" href=\"index.php\">Terug naar de startpagina</a></div>";
			echo "</div>";
		}
		elseif ($result == "success")
		{
			echo "<h3>Uploaden voltooid</h3>";
			echo "<div class=\"succes\">";
			echo "<p>De afbeelding is met succes geüpload.</p>";
			echo "<p>Een vrijwilliger zal de afbeelding zo snel mogelijk beoordelen en contact met u opnemen.</p>";
			echo "<div class=\"wizardfooter\"><a class=\"button upload\" href=\"upload.php\">Nog een afbeelding uploaden</a></div>";
			echo "</div>";
		}
		else
		{
			echo "<h3>Het advies van de uploadwizard is:</h3>";
			echo "<div class=\"succes\">";
			echo "<p>Upload de afbeelding onder de CC-BY-SA licentie</p>";
			echo "<div class=\"wizardfooter\"><a class=\"button upload\" href=\"upload.php\">Uploaden</a></div>";
			echo "</div>";			   }
	}
?>
