<?php
	require 'common/bootstrap.php';
    require 'common/header.php';
?>
<div id="content">
    <?php
	if (!isset($_GET['image']) || !isset($_GET['key']))	:
	    echo "<div class=\"box red\">Geen afbeelding gevonden!</div>";
	else:
	    $row = DB::queryFirstRow("SELECT * FROM images WHERE id = %d", $_GET['image']);

	    if (DB::count() == 0 || $row['key'] != $_GET['key']):
			echo "<div class=\"box red\">Geen afbeelding gevonden!</div>";
	    else:
    ?>

    <h2>Inzending volgen: <?php echo $row['title']; ?> </h2>

    <div class="single">
		<div class="single-box image">
		    <a href="uploads/<?php echo $row['filename']; ?>"><img src="uploads/<?php echo $row['filename'] ;?>" /></a>
		</div>

		<div class="single-box info">
		    <h3>Informatie</h3>

		    <div class="holder">
			<div><span class="title">Titel:</span><span class="content"><?= htmlspecialchars($row['title']); ?></span></div>
			<div><span class="title">Auteursrechthebbende:</span><span class="content"><?= htmlspecialchars($row['source']); ?></span></div>
			<div><span class="title">Geupload door:</span><span class="content"><?= htmlspecialchars($row['name']); ?></span></div>
			<div><span class="title">Beschrijving:</span><span class="content"><?= htmlspecialchars($row['description']);?></span></div>
		    </div>
		</div>

		<div class="single-box options">

		    <h3>Status</h3>

		    <?php
			if ($row['archived'] == 1) {
			    echo "<div class=\"box green\"><i class=\"fa fa-check fa-lg\"></i>Afgehandeld</div>";
			} elseif ($row['archived'] == 0 && $row['owner'] != 0) {
			    echo "<div class=\"box grey\"><i class=\"fa fa-clock-o fa-lg\"></i>In behandeling</div>";
			} elseif ($row['archived'] == 0 && $row['owner'] == 0) {
			    echo "<div class=\"box grey\"><i class=\"fa fa-clock-o fa-lg\"></i>In de wachtrij</div>";
			} else {
			    echo "<div class=\"box red\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i>Status onbekend</div>";
			}
		    ?>
		</div>
    </div>

    <?php
		endif;
    endif;
    ?>

</div>

<script src="<?php echo $basispad ?>/scripts/jquery.imagelightbox.min.js"></script>
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
	include 'common/footer.php';
?>
