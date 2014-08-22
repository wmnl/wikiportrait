<?php
	header('HTTP/1.1 404 Not Found');
	header('Status: 404 Not Found');
	include 'header.php';
?>          
	<div id="content">
		<h2>Oei, deze pagina bestaat niet!</h2>
		<p>Hier moet dan een begeleidend tekstje staan.</p>
	</div>
<?php
	include 'footer.php';
?>