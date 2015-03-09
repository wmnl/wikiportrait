<?php
    require 'common/header.php';
    require 'common/formfunctions.php';
    require 'common/uploadfunctions.php';

    if (isset($_POST['postback'])) {
    	checkUpload();
    }
?>
<script>
	function comparenames() {
		var depicted = document.getElementById('title').value;
		var photographer = document.getElementById('source').value;

		if (depicted.length > 0 && photographer.length > 0 && (depicted == photographer)) {
			swal('U hebt aangegeven dat de persoon op de foto dezelfde persoon is als de auteursrechthebbende', 'Let erop dat alleen de fotograaf toestemming kan geven om de foto vrij te geven onder een vrije licentie.', 'warning');
		}
	}
</script>
<div id="content">
    <?php
	if (hasvalidationerrors()) {
	    showvalidationsummary();
	}
    ?>

    <h2>Uploadformulier</h2>

    <form method="post" enctype="multipart/form-data">

	<div class="input-container">
	    <label for="file"><i class="fa fa-file-image-o fa-lg fa-fw"></i>Kies een bestand</label>
	    <div class="file">
		<input type="file" name="file" id="file" required="required" accept="image/*" />
	    </div>
	</div>

	<div class="input-container">
	    <label for="title"><i class="fa fa-eye fa-lg fa-fw"></i>Afgebeeld persoon</label>
		<input type="text" name="title" id="title" required="required" value="<?php if (!empty($title)) echo $title; ?>" onblur="comparenames()" />
	</div>

	<div class="input-container">
	    <label for="source"><i class="fa fa-camera fa-lg fa-fw"></i>Auteursrechthebbende</label>
		<input type="text" name="source" id="source" required="required" value="<?php if (!empty($_POST['source'])) echo $_POST['source']; ?>" onblur="comparenames()" />
	</div>

	<div class="input-container">
	    <label for="name"><i class="fa fa-user fa-lg fa-fw"></i>Uw naam</label>
	    <input type="text" name="name" id="name" required="required" value="<?php if (!empty($_POST['name'])) echo $_POST['name']; ?>" />
	</div>

	<div class="input-container">
	    <label for="email"><i class="fa fa-envelope fa-lg fa-fw"></i>Uw e-mailadres</label>
	    <input type="email" id="email" name="email" required="required" value="<?php if (!empty($_POST['email'])) echo $_POST['email']; ?>" />
	</div>

	<div class="input-container">
	    <label for="date"><i class="fa fa-calendar fa-lg fa-fw"></i>Opnamedatum <span class="optional">(optioneel)</span></label>
	    <input type="date" name="date" id="date" value="<?php if (!empty($_POST['date'])) echo $_POST['date']; ?>" />
	</div>

	<div class="input-container">
	    <label for="description"><i class="fa fa-comment fa-lg fa-fw"></i>Omschrijving <span class="optional">(optioneel)</span></label>
	    <textarea name="description" id="description"><?php if (!empty($_POST['description'])) echo $_POST['description']; ?></textarea>
	</div>

	<div class="input-container">
	    <label><i class="fa fa-bars fa-lg fa-fw"></i> Licentievoorwaarden</label>
	    <textarea disabled="disabled">Door het uploaden van dit materiaal en het klikken op de knop 'Upload foto' verklaart u dat u de rechthebbende eigenaar bent van het materiaal. Door dit materiaal te uploaden geeft u toestemming voor het gebruik van het materiaal onder de condities van de door u geselecteerde licentie(s), deze condities variëren per licentie maar houden in ieder geval in dat het materiaal verspreid, bewerkt en commercieel gebruikt mag worden door eenieder. Voor de specifieke extra condities per licentie verwijzen wij u naar de bijbehorende licentieteksten. Na het vrijgeven kunt u niet meer terugkomen op deze rechten. De Wikimedia Foundation en haar chapters (waaronder de Vereniging Wikimedia Nederland) zijn op geen enkele wijze aansprakelijk voor misbruik van het materiaal of aanspraak op het materiaal door derden. De eventuele geportretteerden hebben geen bezwaar tegen publicatie onder genoemde licenties. Ook uw eventuele opdrachtgever geeft toestemming.</textarea>
	</div>

	<div class="bottom right">
	    <button class="green" type="submit" name="postback"><i class="fa fa-cloud-upload fa-lg"></i>Upload foto</button>
	</div>

    </form>
</div>

<?php
	include 'common/footer.php';
?>