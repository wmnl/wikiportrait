<?php
    require 'common/bootstrap.php';
    require 'common/header.php';
    if (!isset($_GET['confirmid'])) { ?>
	    <div id="content">
		<h2>Bevestiging mislukt</h2>
		<p>Er is geen bevestigingscode meegestuurd. Gebruik wel de link uit de mail om te bevestigen.</p>
	    </div>
    <?php } else {
	
    }