<?php
	include '../header.php';
	checkLogin();
?>

<div id="content">

	<?php
		setlocale(LC_ALL, 'nl_NL');
		if (!isset($_GET['id']))
			echo "Er is geen ID opgegeven!";
		else
		{
			$query = sprintf("SELECT * FROM images WHERE id = %d", mysqli_real_escape_string($connection, $_GET['id']));
			$result = mysqli_query($connection, $query);

			if (mysqli_num_rows($result) == 0)
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
					
					$query = sprintf("UPDATE images SET owner = %d, archived = %d WHERE id = %d", mysqli_real_escape_string($connection, $_POST['owner']), $done , mysqli_real_escape_string($connection, $_GET['id']));
					mysqli_query($connection, $query);
					echo "<div class=\"box green\">" . $query . "</div>";
				   }
				   $row = mysqli_fetch_assoc($result);
	?>
	
	<h2>Ingestuurde foto: <?= $row['title']; ?></h2>
	
	<div class="single">
	
		<div class="single-box image">
		
			<a href="../uploads/<?= $row['filename']; ?>" target="_blank"><img src="../uploads/<?= $row['filename'] ;?>" /></a>
		
		</div>
		
		<div class="single-box info">
		
			<h3>Informatie</h3>
	
			<div class="holder">
				<div><span class="title">Titel:</span><span class="content"><?= htmlspecialchars($row['title']); ?></span></div>
				<div><span class="title">Auteursrechthebbende:</span><span class="content"><?= htmlspecialchars($row['source']); ?></span></div>
				<div><span class="title">Naam:</span><span class="content"><?= htmlspecialchars($row['name']); ?></span></div>
				<div><span class="title">IP-adres:</span><span class="content"><?= $row['ip']; ?></span></div>
				<div><span class="title">Geüpload op:</span><span class="content"><?= strftime("%e %B %Y om %H:%I:%S", $row['timestamp']) ?></span></div>
				<div><span class="title">Beschrijving:</span><span class="content"><?= htmlspecialchars($row['description']);?></span></div>
			</div>
		
			<h3>Wat doen we ermee?</h3>

			<ul class="list">
				<li><a href="//commons.wikimedia.org/wiki/Special:Upload?&uploadformstyle=basicwp&wpUploadDescription={{Information%0A|Description={{nl|1=<?= $row['title'] ?>}}%0A|Source=wikiportret.nl%0A|Permission=CC-BY-SA 3.0%0A|Date=<?= $row['date']; ?>%0A|Author=<?= $row['source']; ?>%0A}}%0A{{wikiportrait|}}" target="_blank">Uploaden naar Commons!</a></li>
				<?php
					$query = "SELECT * FROM messages";
					$result = mysqli_query($connection, $query);
	
					while($row = mysqli_fetch_assoc($result))
					{
				?>
				<li><a href="message.php?message=<?= $row['id']; ?>&image=<?= mysqli_real_escape_string($connection, $_GET['id']) ?>"><?= $row['title'] ?></a></li>
				<?php
				}
				?>
			</ul>
			
		</div>
		
		<div class="single-box options">

			<h3>Opties</h3>
			
			<form method="post" id="owner" name="owner">
	
				<div class="input-container">
		
					<label for="owner"><i class="fa fa-user-md fa-lg fa-fw"></i>Eigenaar</label>
		
					<select class="select" name="owner" id="setowner">
						<option value="0">----</option>
						<?php
						    $query = sprintf("SELECT owner FROM images WHERE id = %d", mysqli_real_escape_string($connection, $_GET['id']));
						    $result = mysqli_query($connection,$query);
						    $owner = mysqli_fetch_assoc($result);
						    $query2 = "SELECT otrsname, id FROM users";
						    $result2 = mysqli_query($connection,$query2);
						    
						    while ($row = mysqli_fetch_assoc($result2)):
							$selected = "";
						    
						    if ($row['id'] == $owner['owner'])
							$selected = "selected=\"selected\"";
						?>
						<option value="<?= $row['id'] ?>" <?= $selected ?>><?= $row['otrsname'] ?></option>
						<?php
						    endwhile;
						?>
					</select>
				</div>
		
				<div class="input-container">
					<label for="done"><i class="fa fa-check fa-lg fa-fw"></i>Afgehandeld</label>
					<div class="checkbox">
							<input type="checkbox" name="done" id="done" /><label for="done">Ja</label>
					</div>
				</div>
				
				<div class="bottom right">
					<button type="button" onClick="parent.location='get.php?id=<?= $_GET['id'] ?>'" name="claim"><i class="fa fa-bolt fa-lg"></i>Ik neem hem</button><span class="divider">&nbsp;</span><button class="green" type="submit" name="postback"><i class="fa fa-floppy-o fa-lg"></i>Opslaan</button>
				</div>
		
			</form>
		 
		</div>
	
	</div>

	<?php
				 }
		}
	?>
	
</div>

<script src="<?php echo $basispad ?>/scripts/imagelightbox.min.js"></script>
<script>
$( function(){
	var activityIndicatorOn = function()
	{
		$( '<div id="imagelightbox-loading"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></div>' ).appendTo( 'body' );
	},
	activityIndicatorOff = function()
	{
		$( '#imagelightbox-loading' ).remove();
	},

	overlayOn = function()
	{
		$( '<div id="imagelightbox-overlay"></div>' ).appendTo( 'body' );
	},
	overlayOff = function()
	{
		$( '#imagelightbox-overlay' ).remove();
	},

	closeButtonOn = function( instance )
	{
		$( '<button type="button" id="imagelightbox-close" title="Close"><i class="fa fa-times fa-lg"></i></button>' ).appendTo( 'body' ).on( 'click touchend', function(){ $( this ).remove(); instance.quitImageLightbox(); return false; });
	},
	closeButtonOff = function()
	{
		$( '#imagelightbox-close' ).remove();
	};

	var instanceC = $( 'a' ).imageLightbox(
	{
		onStart:		function() { overlayOn(); closeButtonOn( instanceC ); },
		onEnd:			function() { closeButtonOff(); overlayOff(); activityIndicatorOff(); },
		onLoadStart: 	function() { activityIndicatorOn(); },
		onLoadEnd:	 	function() { activityIndicatorOff(); }
	});
});
</script>

<?php
	include '../footer.php';
?>
