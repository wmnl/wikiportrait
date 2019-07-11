<?php
use Intervention\Image\ImageManager;
use Handlebars\Handlebars;

function removeAccentedCharacters($str) {
    return strtr($str, [
        'á'=>'a','à'=>'a','ä'=>'a','â'=>'a','é'=>'e','è'=>'e','ê'=>'e',
        'ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ò'=>'o','ó'=>'o',
        'ô'=>'o','ö'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ç'=>'c'
    ]);
}

function getFilename($title, $time, $file) {
    $title = removeAccentedCharacters($title);
    $title = preg_replace("/[^A-Za-z0-9 ]/", '', $title);
    $title = strtolower(str_replace(" ", "_", $title));
    $datestamp = date_timestamp_get($time);
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    return sprintf("%s-%s.%s", $title, $datestamp, $extension);
}

function contributorEmailCheck($email) {
    $row = DB::queryFirstRow('SELECT email, verified FROM contributors WHERE email = %s', $email);
    (DB::count() > 0) ? $check = array(True, $row['verified']) : $check = array(False, 0);
    return $check;
}

// TODO: this should really be refactored in a class...
function checkUpload() {
    global $session, $messages;
    $errors = [];
    $allowedext = ["image/png", "image/gif", "image/jpeg", "image/bmp", "image/pjpeg"];

    $file = $_FILES['file'];
    $title = $_POST['title'];
    $source = $_POST['source'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $ip = $_SERVER["REMOTE_ADDR"];
    $date = $_POST['date'];
    $desc = $_POST['description'];
    $key = sha1_file($file['tmp_name']);

    $fileresult = checkfile($_FILES['file']);
    if($fileresult!=='ok') {
        $session->redirect("/wizard", "?question=failupload");
    }

    if (isDuplicateFile($key)) {
        $session->redirect("/wizard", "?question=duplicate");
    }

    isrequired('title', 'titel');
    isrequired('source', 'auteursrechthebbende');
    isrequired('name', 'naam');
    $mailresult = validateEmail('email');
    if($mailresult!=='ok') {
        $session->redirect("/wizard", "?question=failupload");
    }
    agreeterms('terms', 'de licentievoorwaarden, de privacyverklaring en het opslaan van uw IP-adres');
    agreeterms('euvs', 'de toestemming voor het opslaan van uw gegevens');
    $validateUploader = validateUploader();

    if (!hasvalidationerrors()) {
	// $mail = new \PHPMailer\PHPMailer\PHPMailer();
  $amil = new PHPMailer(); // narcode
	$templateRenderer = new Handlebars;
  list($email_exists, $email_verified) = contributorEmailCheck($email);

	if ($validateUploader) {
	    $archived = 1;
	    $subject = "[Wikiportret] $title is geüpload op Wikiportret";
	    $bodyTxt = file_get_contents(ABSPATH . "/common/mailbody_uploadercheck.txt");
      $redirect = "success";
	} else {
      if ($email_verified) {
      $archived = 0;
      $subject = "[Wikiportret] $title is geüpload op Wikiportret";
      $bodyTxt = file_get_contents(ABSPATH . "/common/mailbody.txt");
      $redirect = "success";
      } else {
	    $archived = 1;
	    $subject = "[Wikiportret] Uw email verifiëren";
	    $bodyTxt = file_get_contents(ABSPATH . "/common/mailbody_verificatie.txt");
      $redirect = "verificatie";
      }
	}

	$time = new DateTime();
	$filename = getFilename($title, $time, $file);

	$imagepath = IMAGE_FOLDER . "/$filename";
	$thumbpath = THUMB_FOLDER . "/$filename";

	if (move_uploaded_file($file['tmp_name'], $imagepath)) {
	    $imageManager = new ImageManager(array('driver' => 'gd'));
	    $thumb = $imageManager->make($imagepath);
	    $thumb->fit(300, 300);
	    $thumb->save($thumbpath);

	    DB::insert('images', [
		'filename' => $filename,
		'title' => $title,
		'source' => $source,
		'name' => $name,
		'email' => $email,
		'license' => 'CC-BY-SA 4.0',
		'ip' => $ip,
		'date' => $date,
		'description' => $desc,
		'timestamp' => date_timestamp_get($time),
		'key' => $key,
		'archived' => $archived,
	    ]);

    if (!$email_exists)
	    DB::insert('contributors', [
		'email' => $email,
		'verified' => 0,
	    ]);

	    $body = $templateRenderer->render($bodyTxt, [
		'title' => $title,
		'name' => $name,
    'email' => $email,
		'source' => $source,
		'desc' => $desc,
		'ip' => $ip,
		'imageId' => DB::insertId(),
		'key' => $key
	    ]);
	    $htmlBody = nl2br($body);

	    $mail->From = OTRS_MAIL;
	    $mail->CharSet = 'UTF-8';

	    if ($validateUploader) {
      $mail->addAddress($email, $name);
      $mail->addReplyTo(OTRS_MAIL, "Wikiportret OTRS queue");
      } else {
      $email_verified ? $mail->addAddress(OTRS_MAIL, "Wikiportret OTRS queue") : $mail->addAddress($email, "Wikiportret OTRS queue");
      $email_verified ? $mail->addReplyTo($email, $name) : $mail->addReplyTo(OTRS_MAIL, "Wikiportret OTRS queue");
      };

	    $mail->Subject = $subject;
	    $mail->isHTML(true);
	    $mail->Body = $htmlBody;
	    $mail->AltBody = $body;

	    if (!$mail->send()) {
		$session->redirect("/wizard", "?question=failupload");
	    } else {
    $session->setLastUploadKey($key);
    $session->setLastUploadEmail($email);
    $session->redirect("/wizard", "?question=$redirect");
	    }
	  }
  }
}
