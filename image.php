<?php 
	include 'header.php';
	checkLogin();
?>			

<div id="content">
	<?php
		setlocale(LC_ALL, 'nl_NL');
		if (!isset($_GET['id']))
			echo "Er is geen ID opgegeven!";
		else
		{
			$query = sprintf("SELECT * FROM images WHERE id = %d", mysql_real_escape_string($_GET['id']));
			$result = mysql_query($query);

			if (mysql_num_rows($result) == 0)
			{
				echo "Foto niet gevonden!";
			}
			else
			{
				if (isset($_POST['postback']))
				{
				    if (isset($_POST['done']))
					$done = 1;
				    else
					$done = 0;
				    
				    $query = sprintf("UPDATE images SET owner = %d, archived = %d WHERE id = %d", mysql_real_escape_string($_POST['owner']), $done , mysql_real_escape_string($_GET['id']));
				    mysql_query($query);
				    echo $query;
				}
				$row = mysql_fetch_assoc($result);
	?>
	<h2>Ingestuurde foto: <?= $row['title']; ?></h2>

	<a href="uploads/<?= $row['filename']; ?>" target="_blank" class="float-right"><img src="uploads/<?= $row['filename'] ;?>" style="max-width:10em;" /></a>

	<h3>Informatie</h3>

	<p>
		<ul>
			<li>Titel: <?= $row['title']; ?></li>
			<li>Auteur: <?= $row['source']; ?></li>
			<li>Naam uploader: <?= $row['name']; ?></li>
			<li>IP uploader: <?= $row['ip']; ?></li>
			<li>Geüpload op: <?= strftime("%e %B %Y om %H:%I:%S", $row['timestamp']) ?></li>
			<li>Beschrijving: <?= $row['description'];?></li>
		</ul>
	</p>

	<h3>Wat doen we ermee?</h3>
	
	<p>
		<ul>
			<li><a href="https://commons.wikimedia.org/wiki/Special:Upload?&uploadformstyle=basicwp&wpUploadDescription={{Information%0A|Description={{nl|1=<?= $row['title'] ?>}}%0A|Source=wikiportret.nl%0A|Permission=CC-BY-SA 3.0%0A|Date=<?= $row['date']; ?>%0A|Author=<?= $row['source']; ?>%0A}}%0A{{wikiportrait|}}" target="_blank">Uploaden naar Commons!</a></li>
			<?php
				$query = "SELECT * FROM messages";
				$result = mysql_query($query);
	
				while($row = mysql_fetch_assoc($result))
				{
			?>
			<li><a href="message.php?message=<?= $row['id']; ?>&image=<?= mysql_real_escape_string($_GET['id']) ?>"><?= $row['title'] ?></a></li>
			<?php
				}
			?>
		</ul>
	</p>

	<h3>Opties</h3>
	
	<form method="post" id="owner" name="owner">
	
		<div class="input-container">
			<label for="done"><i class="fa fa-check fa-lg fa-fw"></i>Afgehandeld</label>
			<div class="checkbox">
					<input type="checkbox" name="done" id="done" /><label for="admin">Ja</label>
			</div>
		</div>
	
		<div class="input-container">
		
			<label for="owner"><i class="fa fa-user-md fa-lg fa-fw"></i>Eigenaar</label>
			
			<select name="owner" id="setowner" style="width:70%; border-right:0px; border-top-right-radius:0px; border-bottom-right-radius:0px;">
				<option value="0">----</option>
				<?php
					$query2 = "SELECT id, otrsname FROM users";
					$result2 = mysql_query($query2);
					while ($rij = mysql_fetch_assoc($result2))
					{
					    $selected = "";
					    if ($row['owner'] == $rij['id'])
						$selected = "selected=\"selected\"";
					    
					    echo "<option value='" . $rij['id'] . "' $selected>" . $rij['otrsname'] . "</option>";
					}
				?>
			</select>
			<button type="button" name="claim" class="button green" onclick="document.getElementById('setowner').value = <?= $_SESSION['user'] ?>" style="width:30%; display:table-cell; border-top-left-radius:0px; border-bottom-left-radius:0px;"><i class="fa fa-bolt fa-lg"></i><span>Aan mij toewijzen</span></button>
		</div>
	    
	    <div class="input-container bottom">
		<input class="button grey float-right" type="submit" name="postback" value="Opslaan" />
	    </div>
	</form>

	<?php
			}
		}
	?>
	<div class="clear"></div>
</div>

<?php
	include 'footer.php';
?>