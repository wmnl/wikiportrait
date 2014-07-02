<?php 
	include 'header.php';
	checkLogin();
?>			

<script>
	function change(){
		document.getElementById('owner').submit();
	}
</script>
<div id="content">
	<?php
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
				if (isset($_POST['owner']))
				{
					$query = sprintf("UPDATE images SET owner = %d WHERE id = %d", mysql_real_escape_string($_POST['owner']), mysql_real_escape_string($_GET['id']));
					mysql_query($query);
					header("Refresh:0");
				}
				$row = mysql_fetch_assoc($result);
	?>
	<h2>Ingestuurde foto: <?php echo $row['title']; ?></h2>

	<a href="uploads/<?php echo $row['filename']; ?>" target="_blank" class="float-right"><img src="uploads/<?php echo $row['filename'] ;?>" style="max-width:15em;" /></a>

	<h3>Informatie</h3>

	<p>
		<ul>
			<li>Titel: <?php echo $row['title']; ?></li>
			<li>Auteur: <?php echo $row['source']; ?></li>
			<li>Naam uploader: <?php echo $row['name']; ?></li>
			<li>IP uploader: <?php echo $row['ip']; ?></li>
			<li>Geüpload op: <?php echo gmdate("d F Y H:i:s", $row['timestamp']) ?></li>
			<li>Beschrijving: <?php echo $row['description'];?></li>
		</ul>
	</p>

	<h3>Wat doen we ermee?</h3>
	
	<p>
		<ul>
			<li><a href="https://commons.wikimedia.org/wiki/Special:Upload?&uploadformstyle=basicwp&wpUploadDescription={{Information%0A|Description={{nl|1=<?php echo $row['title'] ?>}}%0A|Source=wikiportret.nl%0A|Permission=CC-BY-SA 3.0%0A|Date=<?php echo $row['date']; ?>%0A|Author=<?php echo $row['source']; ?>%0A}}%0A{{wikiportrait|}}" target="_blank">Uploaden naar Commons!</a></li>
			<?php
				$query = "SELECT * FROM messages";
				$result = mysql_query($query);
	
				while($row = mysql_fetch_assoc($result))
				{
			?>
			<li><a href="message.php?message=<?php echo $row['id']; ?>&image=<?php echo mysql_real_escape_string($_GET['id']) ?>"><?php echo $row['title']; ?></a></li>
			<?php
				}
			?>
		</ul>
	</p>

	<h3>Opties</h3>
	
	<form method="post" id="owner" name="owner">
		<div class="input-container bottom">
		
		<label for="owner"><i class="fa fa-user-md fa-lg fa-fw"></i>Eigenaar</label>
		
		<select name="owner" id="setowner" onchange="change()" style="width:75%; border-right:0px; border-top-right-radius:0px; border-bottom-right-radius:0px;">
			<option value="0">----</option>
			<?php
				$query = "SELECT id, otrsname FROM users";
				$result = mysql_query($query);
				while ($rij = mysql_fetch_assoc($result))
				{
					echo '<option value="' . $rij['id'] . '" ' . (($row['owner'] == $rij['id']) ? 'selected="selected"':"") . '>' . $rij['otrsname'] . '</option>';
				}
			?>
		</select>
		<button class="button green" onclick="document.getElementById('setowner').value = <?php echo $_SESSION['user'] ?>; change()" style="width:25%; display:table-cell; border-top-left-radius:0px; border-bottom-left-radius:0px;">Aan mij toewijzen</button>

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