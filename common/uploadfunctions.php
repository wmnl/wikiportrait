<?php
    function checkUpload() {
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

            if (move_uploaded_file($file['tmp_name'], "uploads/" . $filename)) {
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
                    'timestamp' => date_timestamp_get($time),
                    'key' => $key
                ));

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

                header("Location:wizard.php?question=success&key=" . $key);
            }
        }
    }