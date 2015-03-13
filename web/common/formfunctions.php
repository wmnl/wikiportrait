<?php
    $validationerrors = array();

    function showvalidationsummary() {
        echo getvalidationsummary();
    }

    function getvalidationsummary() {
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

    function isrequired($parameter, $property) {
    	if (empty($_POST[$parameter])) {
    	    addvalidationerror("Er is geen $property ingevuld!");
    	}
    }

    function checkusername($username) {
    	DB::query('SELECT * FROM users WHERE username = %s', $username);
    	if (DB::count() != 0) {
    	    addvalidationerror('Deze gebruikersnaam is reeds geregistreerd!');
    	}
    }

    function validateEmail($email) {
    	if (!filter_var($_POST[$email], FILTER_VALIDATE_EMAIL)) {
    	    addvalidationerror('Geen geldig e-mailadres ingevuld!');
    	}
    }

    function comparepassword ($pass1, $pass2) {
    	if ($pass1 != $pass2) {
    	    addvalidationerror('De twee ingevulde wachtwoorden komen niet overeen!');
    	}
    }

    function checkfile ($file) {
    	global $validationerrors;
    	$allowedext = array("image/png", "image/gif", "image/jpeg", "image/bmp", "image/pjpeg");

    	if (!isset($file)){
    	    array_push($validationerrors, "Er is geen bestand geselecteerd");
    	} elseif (!in_array($file['type'], $allowedext)) {
    	    array_push($validationerrors, "Het bestand dat geüpload is, is geen afbeelding of dit bestandsformaat wordt niet ondersteund");
    	}
    }

    function addvalidationerror($message) {
	   global $validationerrors;
	   array_push($validationerrors, $message);
    }

    function hasvalidationerrors() {
	   global $validationerrors;
	   return count($validationerrors) > 0;
    }
?>