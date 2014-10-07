		<div class="footer">
			Wikiportret is een initiatief van Wikimedia Nederland
		</div>
		</div>

		<link href="<?php echo $basispad ?>/scripts/jQuery.SimpleSelect/jquery.simpleselect.css" rel="stylesheet">
		<script src="<?php echo $basispad ?>/scripts/jQuery.SimpleSelect/jquery.simpleselect.min.js"></script>
		<script>
		$(".select").simpleselect({
			displayContainerInside: "container"
		});
		</script>
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
		
		function confirmDelete(id){
		    if (confirm("Weet je zeker dat je dit bericht wilt verwijderen?")) {
			location.href = "deletemessage.php?id=" + id;
		    }
		    else {
			return;
		    }
		}
		</script>
	</body>
</html>
