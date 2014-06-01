<?php
	require "connect.php";
	
	if (isset($_POST['postback']))
	{
		$errors = array();
		$allowedext = array("image/png", "image/gif", "image/jpeg");

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
				$query = "INSERT INTO images(filename, title, source, name, email, ip, date, description, timestamp)"
						. "VALUES('$filename', '$title', '$source', '$name', '$email', '$ip', '$date', '$desc', " . date_timestamp_get($time) . ");";
				mysql_query($query);
				echo $query;
				
				require "PHPMailer/PHPMailerAutoload.php";
				
				$mail = new PHPMailer;
				
				$mail->CharSet = "UTF-8";
				$mail->isSMTP();									  // Set mailer to use SMTP
				$mail->Host = "smtp.upcmail.nl";					  // Specify main and backup server
				$mail->SMTPAuth = false;							  // Enable SMTP authentication

				$mail->From = $email;
				$mail->FromName = $name;
				$mail->addCustomHeader("X-OTRS-Queue:info-nl::wikiportret");  // add extra header for spamfilter exeption
				$mail->addAddress("otrs-test@wikimedia.org", "Wikiportret");  // Add a recipient
				$mail->addReplyTo($email, $name);

				$mail->WordWrap = 50;								 // Set word wrap to 50 characters
				$mail->isHTML(true);								  // Set email format to HTML

				$mail->Subject = $title . " is geüpload op Wikiportret";
				$mail->Body	= "Lorem ipsum dolar cit amet";
				$mail->AltBody = "Lorem ipsum dolar cit amet zonder HTML";

				if(!$mail->send()) {
				   echo "Message could not be sent.";
				   echo "Mailer Error: " . $mail->ErrorInfo;
				   exit;
				}
				
				header("Location:wizard.php?question=success");
			}
		}
	}
?>
	
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" type="text/css" href="style/style.css" />
		<title>Wikiportret - Stel uw foto's ter beschikking</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	</head>
	
	<body>
		<div id="all">
			<div id="header">
				<h1>Wikiportret</h1>
			</div>
			
			<div id="menu">
			   <?php include 'menu.php'; ?>
			</div>
			
			<div id="content">
				<h2>Uploadformulier</h2>
				
				<?php
					if (!empty($errors))
					{
						echo "<div class=\"error\"><ul>";
						
						foreach ($errors as $error)
						{
							echo "<li>" . $error . "</li>";
						}
						
						echo "</ul></div>";
					}
				?>
				
				<form method="post" enctype="multipart/form-data">
				
					<div>
						<input type="file" name="file" id="file" required="required" />
					</div>
					
					<h2>Informatie</h2>
					
					<div>
						<label for="title">Wie staat er op de foto?</label>
						<input type="text" name="title" id="title" required="required" value="<?php if (!empty($title)) echo $title; ?>" />
					</div>
					
					<div>
						<label for="source">Wie is de auteursrechthebbende van de foto?</label>
						<input type="text" name="source" id="source" required="required" value="<?php if (!empty($_POST['source'])) echo $_POST['source']; ?>" />
					</div>
					
					<div>
						<label for="name">Uw naam</label>
						<input type="text" name="name" id="name" required="required" value="<?php if (!empty($_POST['name'])) echo $_POST['name']; ?>" />
					</div>
					
					<div>
						<label for="email">Uw e-mailadres</label>
						<input type="email" id="email" name="email" value="<?php if (!empty($_POST['email'])) echo $_POST['email']; ?>" />
					</div>
					
					<div>
						<label for="date">Datum van de foto (optioneel)</label>
						<input type="date" name="date" id="date" placeholder="YYYY-MM-DD" value="<?php if (!empty($_POST['date'])) echo $_POST['date']; ?>" />
					</div>
					
					<div>
						<label for="description">Omschrijving (optioneel)</label>
						<textarea name="description" id="description"><?php if (!empty($_POST['description'])) echo $_POST['description']; ?></textarea>
					</div>
					
					<div>
						<label for="license">Licentie</label>
						<select id="license" name="license">
							<optgroup label="Aanbeloven licentie">
								<option value="cc-by-sa-3.0">Creative Commons Naamsvermelding-Gelijk delen 3.0</option>
							</optgroup>
							
							<optgroup label="Overige licenties">
								<option value="cc-0">Creative Commons CC0 1.0 Universele Public Domain Dedication</option>
								<option>Hier nog een</option>
								<option>En nog een</option>
							</optgroup>
						</select>
					</div>
					
					<div>
						<label>Licentievoorwaarden</label>
						<textarea readonly="readonly">Door het uploaden van dit materiaal en het klikken op de knop 'Upload foto' verklaart u dat u de rechthebbende eigenaar bent van het materiaal. Door dit materiaal te uploaden geeft u toestemming voor het gebruik van het materiaal onder de condities van de door u geselecteerde licentie(s), deze condities variëren per licentie maar houden in ieder geval in dat het materiaal verspreid, bewerkt en commercieel gebruikt mag worden door eenieder. Voor de specifieke extra condities per licentie verwijzen u naar de bijbehorende licentieteksten. U kunt op het vrijgeven van deze rechten na het akkoord gaan met deze voorwaarden niet meer terugkomen. De Wikimedia Foundation en haar chapters (waaronder de Vereniging Wikimedia Nederland) zijn op geen enkele wijze aansprakelijk voor misbruik van het materiaal of aanspraak op het materiaal door derden. De eventuele geportretteerden hebben geen bezwaar tegen publicatie onder genoemde licenties. Ook uw eventuele opdrachtgever geeft toestemming.</textarea>
					</div>
					
					<p class="bottom">
						<input type="submit" name="postback" value="Versturen" />
					</div>
					
				</form>
			</div>
		</div>
	</body>
</html>