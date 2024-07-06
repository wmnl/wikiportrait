<?php

use Handlebars\Handlebars;

require 'common/bootstrap.php';
require 'common/header.php';
// require ABSPATH . "/ml/web_entities.php";
?>
<div id="content">
    <?php
    if (empty($_GET['key']) && empty($_GET['email'])) :
        echo "<div class=\"box red\">Geen record gevonden!</div>";
    else :
        $key = $_GET['key'];
        $email = $_GET['email'];
        $verified = DB::queryFirstField('SELECT c.verified from contributors c WHERE email=%s', $email);
        $validEmail = DB::queryFirstField("SELECT COUNT(i.id) FROM images i INNER JOIN contributors c ON `key` = %s 
        WHERE `c`.`email` = %s AND `i`.`archived`=2", $key, $email);
        $rows = DB::query("SELECT i.id as imageid, title, source, description,
        name, ip, c.id, c.email, filename
        FROM contributors c
        INNER JOIN images i USING (email) WHERE `c`.`email` = %s AND `i`.`archived`!=0", $email);
        if ($validEmail == 0 || DB::count() == 0) :
            echo "<div class=\"box red\">Geen record gevonden!</div>";
        else :
            if ($verified == 1) :
                echo "<div class=\"box blue\">Uw email is al geverifieerd</div>";
            else :
                foreach ($rows as $row) {
                    DB::update('contributors', [
                        'verified' => 1,
                    ], 'id = %d', $row['id']);
                    // unarchive image so its processed:
                    DB::update('images', [
                        'archived' => 0,
                    ], 'id = %d', $row['imageid']);

                    // send email to OTRS
                    $mail = new \PHPMailer\PHPMailer\PHPMailer();
                    $templateRenderer = new Handlebars();
                    $bodyTxt = file_get_contents(ABSPATH . "/common/mailbody.txt");

                    $body = $templateRenderer->render($bodyTxt, [
                        'title' => $row['title'],
                        'name' => $row['name'],
                        'email' => $email,
                        'source' => $row['source'],
                        'desc' => $row['description'],
                        'ip' => $row['ip'],
                        'imageId' => $row['imageid'],
                        'key' => $key
                    ]);

                    $htmlBody = nl2br($body);
                    $mail->From = OTRS_MAIL;
                    $mail->Sender = OTRS_MAIL;
                    $mail->CharSet = 'UTF-8';
                    $mail->addAddress(OTRS_MAIL, "Wikiportret OTRS queue");
                    $mail->addReplyTo($email, $row['name']);
                    $mail->Subject = "[Wikiportret] " . $row['title'] . " is geüpload op Wikiportret";
                    $mail->isHTML(true);
                    $mail->Body = $htmlBody;
                    $mail->AltBody = $body;

                    $mail->send();
                    // ML analysis
                    if (activeGVRequests()) {
                        detect_web($row['filename']);
                    }
                }
    ?>

                <h2>Email geverifieerd, Bedankt!</h2>

                <div class="single">
                    <div class="single-box info">
                        <h3>Informatie</h3>
                        <?php
                        echo "<div class=\"box green\">De afbeelding is met succes geüpload.<br />
                  Een vrijwilliger zal de afbeelding zo snel mogelijk beoordelen en contact met u opnemen.<br />
                  Als u wilt, kunt u <a href=\"track.php?key=$key\">hier</a> uw inzending volgen.</div>";
                        echo "<div class=\"bottom right\"><a class=\"button\" href=\"upload.php\">
                  <i class=\"fa fa-cloud-upload fa-lg\"></i>Nog een afbeelding uploaden</a></div>";
                        ?>
                    </div>
                </div>
</div>

<?php
            endif;
        endif;
    endif;
?>

</div>

<script src="<?= $basispad ?>/lib/imagelightbox/dist/imagelightbox.min.js"></script>
<script src="<?= $basispad ?>/js/lightbox.js"></script>

<?php
include 'common/footer.php';
?>
