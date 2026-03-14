<?php

//@phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
use Handlebars\Handlebars;
use PHPMailer\PHPMailer\PHPMailer;

require_once '../common/bootstrap.php';
$session->checkAdmin();

$mail             = new PHPMailer();
$templateRenderer = new Handlebars();

function getContribution(string $id, string $hash): array
{
    if (filter_var($id, FILTER_VALIDATE_INT) === false) {
        throw new InvalidArgumentException("Geen geldige id");
    }
    if (!ctype_xdigit($hash) || strlen($hash) !== 40) {
        throw new InvalidArgumentException("Geen geldige hash");
    }

    $contribution = DB::queryFirstRow(
        "SELECT email, archived FROM images WHERE id=%i AND `key`=%s",
        $id,
        $hash
    );

    if (empty($contribution['email']) || $contribution['archived'] !== "2") {
        throw new RuntimeException('Kan mailadres niet goedkeuren.');
    }

    return $contribution;
}

function buildMail(
    PHPMailer $mail,
    string $to,
    string $toName,
    string $replyTo,
    string $replyToName,
    string $subject,
    string $body
): void {
    $mail->clearAddresses();
    $mail->clearReplyTos();
    $mail->From    = VRTS_MAIL;
    $mail->Sender  = VRTS_MAIL;
    $mail->CharSet = 'UTF-8';
    $mail->addAddress($to, $toName);
    $mail->addReplyTo($replyTo, $replyToName);
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body    = nl2br($body);
    $mail->AltBody = $body;
}

function verify(string $id, string $hash, PHPMailer $mail, Handlebars $templateRenderer): void
{
    $contribution = getContribution($id, $hash);

    $validEmail = DB::queryFirstField(
        "SELECT COUNT(i.id) FROM images i INNER JOIN contributors c ON `key` = %s 
         WHERE `c`.`email` = %s AND `i`.`archived`=2",
        $hash,
        $contribution['email']
    );

    $rows = DB::query(
        "SELECT i.id as imageid, title, source, description, name, ip, c.id, c.email, filename
         FROM contributors c
         INNER JOIN images i USING (email)
         WHERE `c`.`email` = %s AND `i`.`archived`!=0",
        $contribution['email']
    );

    if ($validEmail == 0 || DB::count() == 0) {
        throw new RuntimeException('Kan mailadres niet goedkeuren.');
    }

    $bodyTpl = file_get_contents(ABSPATH . "/common/mailbody.txt");

    foreach ($rows as $row) {
        DB::update('contributors', ['verified' => 1], 'id = %d', $row['id']);
        DB::update('images', ['archived' => 0], 'id = %d', $row['imageid']);

        $body = $templateRenderer->render($bodyTpl, [
            'title'   => $row['title'],
            'name'    => $row['name'],
            'email'   => $contribution['email'],
            'source'  => $row['source'],
            'desc'    => $row['description'],
            'ip'      => $row['ip'],
            'imageId' => $row['imageid'],
            'key'     => $hash,
        ]);

        buildMail(
            $mail,
            VRTS_MAIL,
            "Wikiportret VRTS queue",
            $contribution['email'],
            $row['name'],
            "[Wikiportret] {$row['title']} is geüpload op Wikiportret",
            $body
        );
        $mail->send();

        if (activeGVRequests()) {
            detect_web($row['filename']);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'E-mailadres is geverifieerd en bevestigingsmail is verstuurd.',
    ]);
}

function resend(string $id, string $hash, PHPMailer $mail, Handlebars $templateRenderer): void
{
    $contribution = getContribution($id, $hash);

    $row = DB::queryFirstRow(
        "SELECT i.id as imageid, title, source, description, name, ip, filename
         FROM images i WHERE id=%i AND `key`=%s",
        $id,
        $hash
    );

    $bodyTpl = file_get_contents(ABSPATH . "/common/mailbody_verificatie.txt");
    $body    = $templateRenderer->render($bodyTpl, [
        'title'   => $row['title'],
        'name'    => $row['name'],
        'email'   => $contribution['email'],
        'source'  => $row['source'],
        'desc'    => $row['description'],
        'ip'      => $row['ip'],
        'imageId' => $row['imageid'],
        'key'     => $hash,
    ]);

    buildMail(
        $mail,
        $contribution['email'],
        $row['name'],
        $contribution['email'],
        $row['name'],
        "[Wikiportret] Uw email verifiëren",
        $body
    );

    if (!$mail->send()) {
        throw new RuntimeException('Verificatiemail kon niet worden verstuurd.');
    }

    echo json_encode(['success' => true, 'message' => 'Verificatiemail is opnieuw verstuurd.']);
}

// --- Router ---
try {
    header('Content-Type: application/json');

    $action = $_GET['action'] ?? '';
    $id     = $_GET['id']     ?? '';
    $hash   = $_GET['hash']   ?? '';

    match ($action) {
        'verify' => verify($id, $hash, $mail, $templateRenderer),
        'resend' => resend($id, $hash, $mail, $templateRenderer),
        default  => throw new InvalidArgumentException("Ongeldig verzoek"),
    };
} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ongeldig verzoek.', 'error' => $e->getMessage()]);
} catch (RuntimeException $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Er is iets misgegaan, probeer het opnieuw.',
        'error' => $e->getMessage()
    ]);
}
