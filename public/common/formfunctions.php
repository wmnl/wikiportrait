<?php

use Intervention\Image\ImageManager;

function getCommonsUploadLink($row)
{
    // #57: if description is available, use that, otherwise
    // simply use title
    if (empty($row['description'])) {
        $description = $row['title'];
    } else {
        $description = $row['description'];
    }
    if (empty($row['ticket'])) {
        $otrsTicket = t('commons.ticket_placeholder');
    } else {
        $otrsTicket = $row['ticket'];
    }
    $sourceUrl = BASE_URL . "/uploads/images/" . $row['filename'];
    if ($row['date'] != "") {
        $date = $row['date'];
    }
    $author = $row['source'];
    $filename = $row['filename'];
    $baselink = "https://commons.wikimedia.org/wiki/Special:Upload";
    $categories = '';
    if (array_key_exists('categories', $row)) {
        $categoryList = json_decode($row['categories']);
        if (is_iterable($categoryList)) {
            foreach (json_decode($row['categories']) as $cat) {
                $categories .= '[[Category:' . str_replace('Category:', '', $cat) . ']]' . PHP_EOL;
            }
        }
    }
    if (!isset($date)) {
        $date = null;
    }
    $language = currentLanguage();
    $description = <<<EOT
== {{int:filedesc}} ==
{{Information
    |Description={{{$language}|1=$description}}
    |Source=wikiportret.nl
    |Permission=CC-BY-SA 4.0
    |Date=$date
    |Author=$author
}}
{{wikiportrait2|$otrsTicket}}
$categories
EOT;

    $urlargs = http_build_query([
        "uploadformstyle" => "basicwp",
        "wpSourceType" => "url",
        "wpDestFile" => $filename,
        "wpUploadFileURL" => $sourceUrl,
        "wpUploadDescription" => $description
    ]);

    return sprintf("%s?%s", $baselink, $urlargs);
}

function showvalidationsummary()
{
    echo getvalidationsummary();
}

function getvalidationsummary()
{
    global $validationerrors;

    $html = "";

    if (isset($validationerrors)) {
        $html .= '<div class="box red"><ul>';

        foreach ($validationerrors as $error) {
            $html .= "<li>$error</li>";
        }

        $html .= '</ul></div>';
    }

    return $html;
}

function isrequired($parameter, $property)
{
    if (empty($_POST[$parameter])) {
        addvalidationerror(t('validation.required', ['field' => $property]));
    }
}

function agreeterms($parameter, $property)
{
    if (empty($_POST[$parameter])) {
        addvalidationerror(t('validation.agree', ['terms' => $property]));
    }
}

function checkusername($username)
{
    DB::query('SELECT * FROM users WHERE username = %s', $username);
    if (DB::count() != 0) {
        addvalidationerror(t('validation.username_taken'));
    }
}

function isDuplicateFile($file_hash)
{
    $query = DB::queryRaw('SELECT `key` from images ORDER BY id DESC');
    if ($query->num_rows > 0) {
        while ($files = $query->fetch_array(MYSQLI_NUM)) {
            if (in_array($file_hash, $files)) {
                addvalidationerror(t('error.duplicate'));
                return true;
            }
        }
    }
    return false;
}

function comparepassword($pass1, $pass2)
{
    if ($pass1 != $pass2) {
        addvalidationerror(t('validation.password_mismatch'));
    }
}

function validateEmail($email)
{
    $mailpost = $_POST[$email];
    $mailarrayfull = explode("@", $mailpost);
    $mailarray = array_pop($mailarrayfull);
    if (!filter_var($mailpost, FILTER_VALIDATE_EMAIL)) {
        addvalidationerror(t('validation.invalid_email'));
        return t('validation.invalid_email');
    } elseif (!checkdnsrr($mailarray, "MX")) {
        addvalidationerror(t('validation.invalid_email'));
        return t('validation.invalid_email');
    } else {
        return "ok";
    }
}

function checkfile($file)
{
    global $validationerrors;
    if (!isset($validationerrors)) {
        $validationerrors = [];
    }
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        array_push($validationerrors, t('validation.no_file'));
        return "empty file";
    } elseif (imageExtension($file['tmp_name']) === null) {
        // Never trust $file['type'] or the client file name: inspect the contents
        array_push(
            $validationerrors,
            t('validation.unsupported_format')
        );
        return "unsupported file";
    } else {
        try {
            $filePath = normalizeImageFormat($file['tmp_name']);
            $imageManager = new ImageManager(['driver' => 'gd']);
            $imageManager->make($filePath);
            if ($filePath !== $file['tmp_name']) {
                unlink($filePath);
            }
            return "ok";
        } catch (\Exception $e) {
            array_push(
                $validationerrors,
                t('validation.invalid_image')
            );
            return "invalid image";
        }
    }
}

/**
 * Determines the file extension from the actual image contents.
 * Returns null for anything that isn't a supported image type.
 */
function imageExtension(string $path): ?string
{
    $extensions = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
        IMAGETYPE_GIF  => 'gif',
        IMAGETYPE_BMP  => 'bmp',
    ];
    $info = getimagesize($path);

    return $info === false ? null : ($extensions[$info[2]] ?? null);
}

function addvalidationerror($message)
{
    global $validationerrors;
    if (!isset($validationerrors)) {
        $validationerrors = [];
    }
    array_push($validationerrors, $message);
}

function hasvalidationerrors()
{
    global $validationerrors;
    if (!isset($validationerrors)) {
        return false;
    }
    return count($validationerrors) > 0;
}

function validateUploader($source)
{
    if (strtolower(filter_input(INPUT_POST, 'title')) == strtolower(filter_input(INPUT_POST, 'name'))) {
        return 'selfie';
    } elseif (strtolower($source) == strtolower(filter_input(INPUT_POST, 'name'))) {
        return 'valid';
    } else {
        return 'invalid';
    }
}

function normalizeImageFormat(string $tmpPath): string
{
    $mime = mime_content_type($tmpPath);

    if (
        $mime === 'image/jfif' ||
        pathinfo($tmpPath, PATHINFO_EXTENSION) === 'jfif'
    ) {
        // JFIF is JPEG — lees met GD en schrijf als JPEG terug
        $img = imagecreatefromjpeg($tmpPath);
        if ($img === false) {
            throw new \RuntimeException('Kon JFIF niet inlezen');
        }

        $newPath = $tmpPath . '_converted.jpg';
        imagejpeg($img, $newPath, 95);
        imagedestroy($img);

        return $newPath;
    }

    return $tmpPath;
}