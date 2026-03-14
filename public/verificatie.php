<?php

use Handlebars\Handlebars;

require_once 'common/bootstrap.php';
require_once 'common/header.php';

function processVerification(string $key, string $email): string
{
    $verified   = DB::queryFirstField('SELECT c.verified FROM contributors c WHERE email=%s', $email);
    $validEmail = DB::queryFirstField(
        "SELECT COUNT(i.id) FROM images i 
         INNER JOIN contributors c ON `key` = %s 
         WHERE `c`.`email` = %s AND `i`.`archived`=2",
        $key,
        $email
    );
    $rows = DB::query(
        "SELECT i.id as imageid, title, source, description, name, ip, c.id, c.email, filename
         FROM contributors c
         INNER JOIN images i USING (email)
         WHERE `c`.`email` = %s AND `i`.`archived`!=0",
        $email
    );

    if ($validEmail == 0 || DB::count() == 0) {
        return '<div class="box red">Geen record gevonden!</div>';
    }

    if ($verified == 1) {
        return '<div class="box blue">Uw email is al geverifieerd</div>';
    }

    $mail             = new \PHPMailer\PHPMailer\PHPMailer();
    $templateRenderer = new Handlebars();
    $bodyTpl          = file_get_contents(ABSPATH . "/common/mailbody.txt");

    foreach ($rows as $row) {
        DB::update('contributors', ['verified' => 1], 'id = %d', $row['id']);
        DB::update('images', ['archived' => 0], 'id = %d', $row['imageid']);

        $body = $templateRenderer->render($bodyTpl, [
            'title'   => $row['title'],
            'name'    => $row['name'],
            'email'   => $email,
            'source'  => $row['source'],
            'desc'    => $row['description'],
            'ip'      => $row['ip'],
            'imageId' => $row['imageid'],
            'key'     => $key,
        ]);

        $mail->clearAddresses();
        $mail->clearReplyTos();
        $mail->From    = VRTS_MAIL;
        $mail->Sender  = VRTS_MAIL;
        $mail->CharSet = 'UTF-8';
        $mail->addAddress(VRTS_MAIL, "Wikiportret VRTS queue");
        $mail->addReplyTo($email, $row['name']);
        $mail->Subject = "[Wikiportret] {$row['title']} is geüpload op Wikiportret";
        $mail->isHTML(true);
        $mail->Body    = nl2br($body);
        $mail->AltBody = $body;
        $mail->send();

        if (activeGVRequests()) {
            detect_web($row['filename']);
        }
    }

    return 'verified';
}

// Validatie buiten de functie
$key   = $_GET['key']   ?? '';
$email = $_GET['email'] ?? '';

if (!$key || !$email) {
    $result = '<div class="box red">Geen sleutel of e-mailadres opgegeven!</div>';
} elseif (!ctype_xdigit($key) || strlen($key) !== 40) {
    $result = '<div class="box red">Ongeldige sleutel!</div>';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = '<div class="box red">Ongeldig e-mailadres!</div>';
} else {
    $result = processVerification($key, $email);
}
?>

<div id="content">
    <?php if ($result === 'verified') : ?>
    <h2>Email geverifieerd, Bedankt!</h2>
    <div class="single">
        <div class="single-box info">
            <h3>Informatie</h3>
            <div class="box green">
                De afbeelding is met succes geüpload.<br>
                Een vrijwilliger zal de afbeelding zo snel mogelijk beoordelen en contact met u opnemen.<br>
                Als u wilt, kunt u <a href="track.php?key=<?= htmlspecialchars($key); ?>">hier</a> uw inzending volgen.
            </div>
            <div class="bottom right">
                <a class="button" href="upload.php">
                    <i class="fa-solid fa-cloud-upload fa-lg"></i>Nog een afbeelding uploaden
                </a>
            </div>
        </div>
    </div>
    <?php else : ?>
        <?= $result; ?>
    <?php endif ?>
</div>

<script src="<?= $basispad; ?>/lib/imagelightbox/dist/imagelightbox.min.js"></script>
<script src="<?= $basispad; ?>/js/lightbox.js"></script>

<?php include 'common/footer.php'; ?>