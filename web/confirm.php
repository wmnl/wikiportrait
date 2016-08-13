<?php
require 'common/bootstrap.php';
require 'common/header.php';
if (!isset($_GET['confirmid'])) {
    ?>
    <div id="content">
        <h2>Bevestiging mislukt</h2>
        <p>Er is geen bevestigingscode meegestuurd. Gebruik wel de link uit de mail om te bevestigen.</p>
    </div>
    <?php
} else {
    $confirmid = filter_input(INPUT_GET, 'confirmid', FILTER_SANITIZE_SPECIAL_CHARS);
    $row = DB::queryFirstRow('SELECT `confirmid`,`confirmed` FROM `images` WHERE `confirmid`="' . $confirmid . '"');
    if ($row['confirmid'] !== $confirmid) {
	?>
	<div id = "content">
	    <h2>Bevestiging mislukt</h2>
	    <p>Er is geen geldige bevestigingscode meegestuurd. Gebruik wel de link uit de mail om te bevestigen.</p>
	</div>
	<?php
    } elseif ($row['confirmed'] === true) {
	//Is al bevestigd, dus nieuwe bevestiging niet nodig
    } else {
	DB::update('images', array('confirmed' => true), "confirmid=%s", $confirmid);
    }
}