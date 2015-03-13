<?php
use Intervention\Image\ImageManager;

// TODO: this should really be refactored in a class...

function checkUpload() {
    global $session;
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
    $key = sha1(rand());

    checkfile($_FILES['file']);
    isrequired('title', 'titel');
    isrequired('source', 'auteursrechthebbende');
    isrequired('name', 'naam');
    validateEmail('email');

    if (!hasvalidationerrors()) {
        $time = new DateTime();
        $filename = strtolower(str_replace(" ", "_", $title)) . "-" . date_timestamp_get($time) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);

        $imagepath = IMAGE_FOLDER . "/$filename";
        $thumbpath = THUMB_FOLDER . "/$filename";

        if (move_uploaded_file($file['tmp_name'], $imagepath)) {
            $imageManager = new ImageManager(array('driver' => 'gd'));
            $thumb = $imageManager->make($imagepath);
            $thumb->resize(300, 300);
            $thumb->save($thumbpath);

            DB::insert('images', [
                'filename' => $filename,
                'title' => $title,
                'source' => $source,
                'name' => $name,
                'email' => $email,
                'license' => 'CC-BY-SA 3.0',
                'ip' => $ip,
                'date' => $date,
                'description' => $desc,
                'timestamp' => date_timestamp_get($time),
                'key' => $key
            ]);

            $subject = "[Wikiportret] " . $title . " is geüpload op Wikiportret";
            $body   = "Beste OTRS-vrijwillger, <br />
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

            $headers .= "Reply-To: $name <$email>\r\n";
            $headers .= "Return-Path: $name <$email>\r\n";
            $headers .= "From: Wikiportret <wikiportret@wikimedia.nl>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers .= "X-Priority: 3\r\n";
            $headers .= "X-Mailer: PHP ". phpversion() ."\r\n" ;

            mail("otrs-test@wikimedia.org", $subject, $message, $headers);

            $session->setLastUploadKey($key);
            $session->redirect("/wizard", "?question=success");
        }
    }
}