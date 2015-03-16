<?php
use Intervention\Image\ImageManager;
use Handlebars\Handlebars;

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
            $thumb->fit(300, 300);
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

            $templateRenderer = new Handlebars;

            $subject = $templateRenderer->render($messages['otrsSubject'],
                ['title' => $title]
            );

            $body = $templateRenderer->render($messages['otrsMail'], [
                'title' => $title,
                'source' => $source,
                'desc' => $desc,
                'ip' => $ip,
                'imageId' => DB::insertId()
            ]);

            $headers = $templateRenderer->render($messages['otrsHeaders'], [
                "name" => $name,
                "email" => $email,
                "mailOrigin" => OTRS_MAIL
            ]);

            mail(OTRS_MAIL, $subject, $message, $headers);

            $session->setLastUploadKey($key);
            $session->redirect("/wizard", "?question=success");
        }
    }
}