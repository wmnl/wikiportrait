<?php
require '../common/bootstrap.php';
$session->checkLogin();
if (empty($_GET['id'])) $session->redirect("/images/overview");

require '../common/header.php';
$msg = false;
?>

<div id="content">
    <?php
    $row = DB::queryFirstRow("SELECT * FROM images WHERE id = %d", $_GET['id']);
    $ticketId = $row['ticket'];

    if (DB::count() == 0) {
        die("Foto niet gevonden!");
    }

    if (isset($_POST['postback'])) {
        DB::update('images', [
            'owner' => $_POST['owner'],
            'archived' => !empty($_POST['done']),
            'ticket' => $_POST['ticket']
            ], 'id = %d', $_GET['id']);

        $msg = 'Afbeelding is bijgewerkt. <a href="overview.php">Terug naar overzicht</a>';
    }
    ?>

    <h2>Ingestuurde foto: <?= $row['title']; ?></h2>

    <?php if ($msg): ?>
        <div class="box green"><?= $msg; ?></div>
    <?php endif; ?>

    <div class="single">
        <div class="single-box image">
            <a href="../uploads/images/<?= $row['filename']; ?>" data-lightbox>
                <img src="../uploads/thumbs/<?= $row['filename'] ;?>" />
            </a>
        </div>

        <div class="single-box info">
            <h3>Informatie</h3>

             <ul class="list">
                <li><span>Titel:</span> <?= htmlspecialchars($row['title']); ?></li>
                <li><span>Rechthebbende:</span> <?= htmlspecialchars($row['source']); ?></li>
                <li><span>Geüpload door:</span> <?= htmlspecialchars($row['name']); ?></li>
                <li><span>E-mailadres:</span> <?= htmlspecialchars($row['email']); ?></li>
                <li><span>IP-adres:</span> <?= $row['ip']; ?></li>
                <li><span>Geüpload op:</span> <?= strftime("%e %B %Y om %H:%I:%S", $row['timestamp']) ?></li>
                <li><span>Key:</span><br /><input type="text" id="key" disabled="disabled" value="<?= $row['key']; ?>" /></li>
                <li><span>Beschrijving:</span><br /><?= htmlspecialchars($row['description']);?></li>
            </ul>

            <h3>Wat doen we ermee?</h3>

            <ul class="list">
                <li>
                    <?php
                    $imgurl = urlencode(BASE_URL . "/uploads/images/" . $row['filename']);
                    ?>
                    <a href="https://www.google.com/searchbyimage?image_url=<?= $imgurl ?>" target="_blank">Zoek naar deze afbeelding bij Google</a>
                </li>
                <?php
                $results = DB::query('SELECT * FROM messages');

                foreach($results as $resultRow):
                    ?>
                <li><a href="message.php?message=<?= $resultRow['id']; ?>&image=<?= $_GET['id'] ?>"><?= htmlspecialchars($resultRow['title']); ?></a></li>
                <?php
                endforeach;
                ?>
                <li>
                    <a href="<?php echo getCommonsUploadLink($row); ?>" target="_blank">
                        Uploaden naar Commons!
                    </a>
                </li>
            </ul>
        </div>

        <div class="single-box options">
            <h3>Opties</h3>

            <form method="post" id="owner" name="owner">
                <div class="input-container">
                    <label for="owner"><i class="fa fa-user-md fa-lg fa-fw"></i>Eigenaar</label>

                    <div class="checkbox">
                        <select class="select" name="owner" id="setowner">
                            <option value="0">----</option>
                            <?php
                                $owner = DB::queryFirstRow('SELECT owner, archived FROM images WHERE id = %d', $_GET['id']);
                                $accounts = DB::query("SELECT otrsname, id FROM users WHERE active = 1");

                                foreach($accounts as $row):
                                    $selected = "";

                                    if ($row['id'] == $owner['owner']) {
                                        $selected = 'selected="selected"';
                                    }
                            ?>
                                    <option value="<?= $row['id'] ?>" <?= $selected ?>><?= $row['otrsname'] ?></option>
                            <?php
                                endforeach;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="input-container">
                    <label for="done"><i class="fa fa-check fa-lg fa-fw"></i>Afgehandeld</label>
                    <div class="checkbox">
                        <input type="checkbox" name="done" id="done" <?php if ($owner['archived'] == 1) { echo "checked"; } ?> /><label for="done">Ja</label>
                    </div>
                </div>

                <div class="input-container">
                    <label for="ticket"><i class="fa fa-ticket fa-lg fa-fw"></i>OTRS-ticket:</label>
                    <input type="text" name="ticket" id="ticket" value="<?= $ticketId; ?>">
                </div>

                <div class="bottom right">
                    <button type="button" onClick="parent.location='get.php?id=<?= $_GET['id'] ?>'" name="claim">
                        <i class="fa fa-bolt fa-lg"></i>Ik neem hem
                    </button>

                    <span class="divider">&nbsp;</span>

                    <button class="green" type="submit" name="postback">
                        <i class="fa fa-floppy-o fa-lg"></i>Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= $basispad ?>/lib/imagelightbox/dist/imagelightbox.min.js"></script>
<script src="<?= $basispad ?>/js/lightbox.js"></script>

<?php
include '../common/footer.php';
?>
