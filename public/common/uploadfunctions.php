<?php

//@phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

use Handlebars\Handlebars;
use Intervention\Image\ImageManager;

require_once ABSPATH . "/ml/web_entities.php";
require_once VENDOR_DIR . "/autoload.php";

function removeAccentedCharacters($str)
{
    return strtr($str, [
        'á' => 'a',
        'à' => 'a',
        'ä' => 'a',
        'â' => 'a',
        'é' => 'e',
        'è' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'ö' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'ç' => 'c'
    ]);
}

function getFilename($title, $time, $file)
{
    $title = removeAccentedCharacters($title);
    $title = preg_replace("/[^A-Za-z0-9 ]/", '', $title);
    $title = strtolower(str_replace(" ", "_", $title));
    $datestamp = date_timestamp_get($time);
    // Extensie op basis van de inhoud, nooit van de bestandsnaam van de uploader
    $extension = imageExtension($file['tmp_name']);

    return sprintf("%s-%s.%s", $title, $datestamp, $extension);
}

function contributorEmailCheck($email)
{
    $row = DB::queryFirstRow('SELECT email, verified FROM contributors WHERE email = %s', $email);
    (DB::count() > 0) ? $check = [true, $row['verified']] : $check = [false, 0];
    return $check;
}

function sendLowResolutionMail($mail, $templateRenderer, $email, $name, $title, $width, $height)
{
    $body = $templateRenderer->render(mailTemplate('low_resolution'), [
        'title' => $title,
        'name' => $name,
        'width' => $width,
        'height' => $height,
        'minSize' => MIN_IMAGE_SIZE,
    ]);

    $mail->addAddress($email, $name);
    $mail->addReplyTo(VRTS_MAIL, t('mail.vrts_queue_name'));
    $mail->Subject = t('mail.subject.low_resolution', ['title' => $title]);
    $mail->isHTML(true);
    $mail->Body = nl2br($body);
    $mail->AltBody = $body;

    return $mail->send();
}

// TODO: this should really be refactored in a class...
function checkUpload()
{
    global $session, $messages;

    $file = $_FILES['file'];
    $title = $_POST['title'];
    $source = $_POST['source_self'] == "Yes" ? $_POST['name'] : $_POST['source'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $ip = $_SERVER["REMOTE_ADDR"];
    $date = $_POST['date'];
    $desc = $_POST['description'];

    $fileresult = checkfile($_FILES['file']);
    if ($fileresult !== 'ok') {
        if ($fileresult === 'unsupported file' || $fileresult === 'empty file') {
            return; // validatiefout is al gezet, toon formulier opnieuw met foutmelding
        }
        $session->redirect("/wizard", "?question=failupload");
    }

    $key = sha1_file($file['tmp_name']);

    if (isDuplicateFile($key)) {
        $session->redirect("/wizard", "?question=duplicate");
    }

    isrequired('title', t('field.title'));
    if ($_POST['source_self'] == "Unknown") {
        addvalidationerror(t('validation.source_unknown'));
        return;
    }
    $_POST['source_self'] != "Yes" ? isrequired('source', t('field.source')) : '';
    isrequired('name', t('field.name'));
    $mailresult = validateEmail('email');
    if ($mailresult !== 'ok') {
        $session->redirect("/wizard", "?question=failupload");
    }
    agreeterms('terms', t('terms.license'));
    agreeterms('euvs', t('terms.storage'));
    $validateUploader = validateUploader($source);

    if (!hasvalidationerrors()) {
        $mail = createMailer();
        $templateRenderer = new Handlebars();

        // Te lage resolutie: automatisch afwijzen, niets opslaan en niets naar VRTS sturen
        $dimensions = getimagesize($file['tmp_name']);
        if ($dimensions !== false && min($dimensions[0], $dimensions[1]) < MIN_IMAGE_SIZE) {
            sendLowResolutionMail($mail, $templateRenderer, $email, $name, $title, $dimensions[0], $dimensions[1]);
            $session->setLastUploadEmail($email);
            $session->redirect("/wizard", "?question=lowres");
            return;
        }


        list($email_exists, $email_verified) = contributorEmailCheck($email);

        if ($validateUploader == 'selfie') {
            $archived = 1;
            $subject = t('mail.subject.uploaded', ['title' => $title]);
            $bodyTxt = mailTemplate('uploader_selfie');
            $redirect = "success";
        } elseif ($validateUploader == 'invalid') {
            $archived = 1;
            $subject = t('mail.subject.uploaded', ['title' => $title]);
            $bodyTxt = mailTemplate('uploader_mismatch');
            $redirect = "success";
        } else {
            if ($email_verified) {
                $archived = 0;
                $subject = t('mail.subject.uploaded', ['title' => $title]);
                $bodyTxt = mailTemplate('new_upload');
                $redirect = "success";
            } else {
                $archived = 2;
                $subject = t('mail.subject.verify');
                $bodyTxt = mailTemplate('verification');
                $redirect = "verificatie";
            }
        }

        $time = new DateTime();
        $filename = getFilename($title, $time, $file);
        $imagepath = IMAGE_FOLDER . "/$filename";
        $thumbpath = THUMB_FOLDER . "/$filename";

        $normalizedTmp = normalizeImageFormat($file['tmp_name']);

        if ($normalizedTmp !== $file['tmp_name']) {
            // Geconverteerd bestand — move_uploaded_file werkt hier niet
            $moved = copy($normalizedTmp, $imagepath);
            unlink($normalizedTmp);
        } else {
            $moved = move_uploaded_file($file['tmp_name'], $imagepath);
        }

        if ($moved) {
            $imageManager = new ImageManager(['driver' => 'gd']);
            $thumb = $imageManager->make($imagepath);
            $thumb->fit(300, 300);
            $thumb->save($thumbpath);

            if (!$email_exists) {
                DB::insert('contributors', [
                    'email' => $email,
                    'verified' => 0,
                ]);
            }

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

            if ($validateUploader !== "valid") {
                $mail->addAddress($email, $name);
                $mail->addReplyTo(VRTS_MAIL, t('mail.vrts_queue_name'));
            } else {
                $email_verified ? $mail->addAddress(VRTS_MAIL, t('mail.vrts_queue_name')) :
                    $mail->addAddress($email, t('mail.vrts_queue_name'));
                $email_verified ? $mail->addReplyTo($email, $name) :
                    $mail->addReplyTo(VRTS_MAIL, t('mail.vrts_queue_name'));
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
                if ($archived == 0 && activeGVRequests()) {
                    detect_web($filename);
                }
                $session->redirect("/wizard", "?question=$redirect");
            }
        }
    }
}
