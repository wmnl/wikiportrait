<?php
	require 'config/connect.php';
	require 'config/config.php';
	include 'header.php';

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

		if (!isset($file))
		{
			array_push($errors, "Er is geen bestand geselecteerd");
		}
		elseif (!in_array($file['type'], $allowedext))
		{
			array_push($errors, "Het bestand dat geüpload is, is geen afbeelding of dit bestandsformaat wordt niet ondersteund");
		}

		if (empty($title))
		{
			array_push($errors, "Er is niet ingevuld wie er op de foto staat");
		}

		if (empty($source))
		{
			array_push($errors, "Er is niet ingevuld wie de auteursrechthebbende is");
		}

		if (empty($name))
		{
			array_push($errors, "Er is geen naam ingevuld");
		}

		if (empty($email))
		{
			array_push($errors, "Er is geen e-mailadres ingevuld");
		}
		elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			array_push($errors, "Er is geen geldig e-mailadres ingevuld");
		}

		if (count($errors) == 0)
		{
			$time = new DateTime();
			$filename = strtolower(str_replace(" ", "_", $title)) . "-" . date_timestamp_get($time) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);

			if (move_uploaded_file($file['tmp_name'], "uploads/" . $filename))
			{
				$query = sprintf("INSERT INTO images(filename, title, source, name, email, license, ip, date, description, timestamp)"
								. "VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d);", mysqli_real_escape_string($connection, $filename), mysqli_real_escape_string($connection, $title), mysqli_real_escape_string($connection, $source), mysqli_real_escape_string($connection, $name), mysqli_real_escape_string($connection, $email), 'CC-BY-SA 3.0', mysqli_real_escape_string($connection, $ip), mysqli_real_escape_string($connection, $date), mysqli_real_escape_string($connection, $desc), mysqli_real_escape_string($connection,	 date_timestamp_get($time)));
		mysqli_query($connection, $query);

				require "PHPMailer/PHPMailerAutoload.php";

				$mail = new PHPMailer;

				$mail->CharSet = "UTF-8";
				$mail->isSMTP();								  // Set mailer to use SMTP

		$mail->SMTPAuth	= true;					 // enable SMTP authentication
		$mail->Host		= "mail.wikidate.nl";	 // sets the SMTP server
		$mail->Port		= 587;					 // set the SMTP port for the GMAIL server
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
http://www.wikidate.nl/wikiportret/image.php?id=" . mysqli_insert_id($connection) . "<br />
<br />
Als je vragen hebt over de uploadwizard kun je terecht bij JurgenNL via http://nl.wikipedia.org/wiki/Gebruiker:JurgenNL of eventueel via jurgennl.wp@gmail.com.<br />
<br />
Al vast heel erg bedankt voor je medewerking!<br />";

				if(!$mail->send()) {
				   echo "Message could not be sent.";
				   echo "Mailer Error: " . $mail->ErrorInfo;
				   exit;
				}

				header("Location:wizard.php?question=success&id=" . mysqli_insert_id($connection));
			}
		}
	}
?>
<div id="content">
		<?php
			if (!empty($errors))
			{
				echo "<div class=\"box red\"><ul>";

				foreach ($errors as $error)
				{
					echo "<li>" . $error . "</li>";
				}

				echo "</ul></div>";
			}
		?>

		<h2>Uploadformulier</h2>

		<form method="post" enctype="multipart/form-data">

				<div class="input-container">
					<label for="file"><i class="fa fa-file-image-o fa-lg fa-fw"></i>Kies een bestand</label>
					<input type="file" name="file" id="file" required="required" accept="image/*" />
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
					<input type="email" id="email" name="email" value="<?php if (!empty($_POST['email'])) echo $_POST['email']; ?>" />
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
						<textarea disabled="disabled">Door het uploaden van dit materiaal en het klikken op de knop 'Upload foto' verklaart u dat u de rechthebbende eigenaar bent van het materiaal. Door dit materiaal te uploaden geeft u toestemming voor het gebruik van het materiaal onder de condities van de door u geselecteerde licentie(s), deze condities variëren per licentie maar houden in ieder geval in dat het materiaal verspreid, bewerkt en commercieel gebruikt mag worden door eenieder. Voor de specifieke extra condities per licentie verwijzen u naar de bijbehorende licentieteksten. U kunt op het vrijgeven van deze rechten na het akkoord gaan met deze voorwaarden niet meer terugkomen. De Wikimedia Foundation en haar chapters (waaronder de Vereniging Wikimedia Nederland) zijn op geen enkele wijze aansprakelijk voor misbruik van het materiaal of aanspraak op het materiaal door derden. De eventuele geportretteerden hebben geen bezwaar tegen publicatie onder genoemde licenties. Ook uw eventuele opdrachtgever geeft toestemming.</textarea>
				</div>

				<div class="bottom right">
						<button class="green" type="submit" name="postback"><i class="fa fa-cloud-upload fa-lg"></i>Upload foto</button>
				</div>

		</form>
</div>
<?php
	include 'footer.php';
?>
