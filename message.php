<?php 
	include 'header.php';
	checkLogin();
?>
<div id="content">
	<h2>Kant-en-klaar berichtje versturen</h2>

	<?php
	
		if (!isset($_GET['image']) || !isset($_GET['message']))
		{
		    echo "<div class=\"error\"><ul>";
		    echo "<li>Bericht of afbeelding niet gevonden!</li>";
		    echo "</ul></div>";
		}
		else
		{
	
		    $query = sprintf("SELECT * FROM messages
		    LEFT JOIN users
		    ON users.id = '%d'
		    LEFT JOIN images
		    ON images.id = '%d'
		    WHERE messages.id = '%d'", mysqli_real_escape_string($connection, $_SESSION['user']), mysqli_real_escape_string($connection, $_GET['image']), mysqli_real_escape_string($connection, $_GET['message']));
	    $result = mysqli_query($connection, $query);
	    $row = mysqli_fetch_assoc($result);

	    $templates = array("%%name%%", "%%title%%", "%%otrsname%%");
	    $replace = array($row['name'], $row['title'], $row['otrsname']);
	?>
	<textarea onclick="this.focus();this.select()"><?php echo str_replace($templates, $replace, $row['message']); ?></textarea>
	<?php
		}
	?>
	<div class="clear"></div>
</div>
<?php
	include 'footer.php';		 
?>