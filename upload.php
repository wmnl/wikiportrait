<?php
    include 'common/header.php';
    require_once 'common/formfunctions.php';

    if (isset($_POST['postback']))
    {
	$errors = array();
	$allowedext = array("image/png", "image/gif", "image/jpeg", "image/bmp", "image/pjpeg");

	$file = $_FILES['file'];
	$title = $_POST['title'];
	$source = $_POST['source'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$ip = $_SERVER["REMOTE_ADDR"];
	$date = $_POST['date'];
	$desc = $_POST['description'];

	checkfile($_FILES['file']);
	isrequired('title', 'titel');
	isrequired('source', 'auteursrechthebbende');
	isrequired('name', 'naam');
	validateEmail('email');

	if (!hasvalidationerrors())
	{
	    $time = new DateTime();
	    $filename = strtolower(str_replace(" ", "_", $title)) . "-" . date_timestamp_get($time) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);

	    if (move_uploaded_file($file['tmp_name'], "uploads/" . $filename))
	    {
		/* Tumpneel maken (Hopelijk doetiet) */

		$x=300; //Width
		$folder="uploads/thumbs"; //Foldername thumbnail

		$size = getimagesize("uploads/" . $filename);

		$scale = $size[0]/$x;
		$y = $size[1]/$scale;

		$thumb = imagecreatetruecolor($x,$y);

		if (in_array($file['type'],array("image/jpeg","image/pjpeg")))
		{
			$img=imagecreatefromjpeg("uploads/" . $filename);
			//$thumb=imagescale($img,$x);
			imagecopyresized($thumb,$img,0,0,0,0,$x,$y,$size[0],$size[1]);
			imagejpeg($thumb,$folder."/".$filename);
		}
		elseif ($file['type']=="image/png")
		{
			$img=imagecreatefrompng("uploads/" . $filename);
			//$thumb=imagescale($img,$x);
			imagecopyresized($thumb,$img,0,0,0,0,$x,$y,$size[0],$size[1]);
			imagepng($thumb,$folder."/".$filename);
		}
		elseif ($file['type']=="image/gif")
		{
			$img=imagecreatefromgif("uploads/" . $filename);
			//$thumb=imagescale($img,$x);
			imagecopyresized($thumb,$img,0,0,0,0,$x,$y,$size[0],$size[1]);
			imagegif($thumb,$folder."/".$filename);
		}
		elseif ($file['type']=="image/bmp")
		{
			$img=imagecreatefromwbmp("uploads/" . $filename);
			//$thumb=imagescale($img,$x);
			imagecopyresized($thumb,$img,0,0,0,0,$x,$y,$size[0],$size[1]);
			imagewbmp($thumb,$folder."/".$filename);
		}
		/* Klaar met Tumpneel */

		DB::insert('images', array (
		    'filename' => $filename,
		    'title' => $title,
		    'source' => $source,
		    'name' => $name,
		    'email' => $email,
		    'license' => 'CC-BY-SA 3.0',
		    'ip' => $ip,
		    'date' => $date,
		    'description' => $desc,
		    'timestamp' => date_timestamp_get($time)
		));

		require "PHPMailer/PHPMailerAutoload.php";

		$mail = new PHPMailer;

		$mail->CharSet = "UTF-8";
		$mail->isSMTP();								  // Set mailer to use SMTP

		$mail->SMTPAuth	= true;					 // enable SMTP authentication
		$mail->Host	= "mail.wikidate.nl";	 // sets the SMTP server
		$mail->Port	= 587;					 // set the SMTP port for the GMAIL server
		$mail->Username	= "info@wikidate.nl";	 // SMTP account username
		$mail->Password	= "henk";				 // SMTP account password

		$mail->From = 'wikidate@axc.nl';
		$mail->FromName = 'Wikiportret';
		$mail->addAddress('otrs-test@wikimedia.org', 'otrs-test');			  // Add a recipient
		$mail->addReplyTo($email, $name);

		$mail->WordWrap = 50;								 // Set word wrap to 50 characters
		$mail->isHTML(true);								  // Set email format to HTML

		$mail->Subject = "[Wikiportret] " . $title . " is geüpload op Wikiportret";
		$mail->Body	= "Beste OTRS-vrijwillger, <br />
zojuist is er op http://www.wikiportret.nl een nieuwe foto geupload. <br />
Deze foto heeft als titel '$title', gemaakt door '$source' onder de licentie 'CC-BY-SA 3.0' met als omschrijving '$desc'<br />
De uploader heeft het volgende IP-adres: '$ip'<br />
<br />
Je kunt de foto bekijken op Wikiportret en de foto daar afwijzen, of een tekst genereren die je kan copy-pasten om een e-mail te schrijven.<br />
<br />
Klik op deze link:<br />
https://www.wikidate.nl/wikiportret/images/single.php?id=" . DB::insertId() . "<br />
<br />
Als je vragen hebt over de uploadwizard kun je terecht bij JurgenNL via https://commons.wikimedia.org/wiki/User:JurgenNL of eventueel via jurgennl.wp@gmail.com.<br />
<br />
Al vast heel erg bedankt voor je medewerking!<br />";

		if(!$mail->send()) {
		   echo "Er is een fout opgetreden tijdens het verzenden van de Wikiportret mail.";
		   echo "Foutgegevens van de PHPMailer: " . $mail->ErrorInfo;
		   exit;
		}

		header("Location:wizard.php?question=success&id=" . DB::insertId());
	    }
	}
    }
?>
<div id="content">
    <?php
	if (hasvalidationerrors())
	{
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
	    <input type="text" name="title" id="title" required="required" value="<?php if (!empty($title)) echo $title; ?>" />
	</div>

	<div class="input-container">
	    <label for="source"><i class="fa fa-camera fa-lg fa-fw"></i>Auteursrechthebbende</label>
	    <input type="text" name="source" id="source" required="required" value="<?php if (!empty($_POST['source'])) echo $_POST['source']; ?>" />
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