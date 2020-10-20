<?php

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
        $otrsTicket = "VUL_HIER_HET_TICKET_NUMMER_IN";
    } else {
        $otrsTicket = $row['ticket'];
    }
    $sourceUrl = BASE_URL . "/uploads/images/" . $row['filename'];
    if ($row['date'] != "") {
        $date = date('Y-m-d', strtotime($row['date']));
    }
    $author = $row['source'];
    $filename = $row['filename'];
    $baselink = "https://commons.wikimedia.org/wiki/Special:Upload";
    $categories = '';
    if (array_key_exists('categories', $row)) {
        $categoriesList = json_decode($row['categories']);
        if (is_array($categoriesList)) {
            foreach ($categoriesList as $cat) {
                $categories .= '[[Category:' . str_replace('Category:', '', $cat) . ']]' . PHP_EOL;
            }
        }
    }
    $description = <<<EOT
== {{int:filedesc}} ==
{{Information
    |Description={{nl|1=$description}}
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
        addvalidationerror("Er is geen $property ingevuld!");
    }
}

function agreeterms($parameter, $property)
{
    if (empty($_POST[$parameter])) {
        addvalidationerror("Gelieve akkoord gaan met $property.");
    }
}

function checkusername($username)
{
    DB::query('SELECT * FROM users WHERE username = %s', $username);
    if (DB::count() != 0) {
        addvalidationerror('Deze gebruikersnaam is reeds geregistreerd!');
    }
}

function isDuplicateFile($file_hash)
{
    $query = DB::queryRaw('SELECT `key` from images ORDER BY id DESC');
    if ($query->num_rows > 0) {
        while ($files = $query->fetch_array(MYSQLI_NUM)) {
            if (in_array($file_hash, $files)) {
                addvalidationerror(DUPLICATE_ERROR);
                return true;
            }
        }
    }
    return false;
}

function comparepassword($pass1, $pass2)
{
    if ($pass1 != $pass2) {
        addvalidationerror('De twee ingevulde wachtwoorden komen niet overeen!');
    }
}

function validateEmail($email)
{
    $mailpost = $_POST[$email];
    $mailarrayfull = explode("@", $mailpost);
    $mailarray = array_pop($mailarrayfull);
    if (!filter_var($mailpost, FILTER_VALIDATE_EMAIL)) {
        addvalidationerror('Geen geldig e-mailadres ingevuld!');
        return "Geen geldig e-mailadres ingevuld!";
    } elseif (!checkdnsrr($mailarray, "MX")) {
        addvalidationerror('Geen geldig e-mailadres ingevuld!');
        return "Geen geldig e-mailadres ingevuld!";
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
    $allowedext = ["image/png", "image/gif", "image/jpeg", "image/bmp", "image/pjpeg"];

    if (!isset($file)) {
        array_push($validationerrors, "Er is geen bestand geselecteerd.");
        return "empty file";
    } elseif (!in_array($file['type'], $allowedext)) {
        array_push($validationerrors, "Het bestand dat geüpload is, is geen afbeelding of dit "
                . "bestandsformaat wordt niet ondersteund.");
        return "unsupported file";
    } else {
        return "ok";
    }
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
    $errorCount = 0;
    if (is_countable($validationerrors)) {
        $errorCount = count($validationerrors);
    }
    return $errorCount > 0;
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
