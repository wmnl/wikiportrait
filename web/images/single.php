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

    if (DB::count() == 0) {
        die("Foto niet gevonden!");
    }

    if (isset($_POST['postback'])) {
        DB::update('images', [
            'owner' => $_POST['owner'],
            'archived' => !empty($_POST['done'])
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
            <a href="../uploads/images/<?= $row['filename']; ?>">
                <img src="../uploads/thumbs/<?= $row['filename'] ;?>" />
            </a>
        </div>

        <div class="single-box info">
            <h3>Informatie</h3>

            <div class="holder">
                <div><span class="title">Titel:</span><span class="content"><?= htmlspecialchars($row['title']); ?></span></div>
                <div><span class="title">Auteursrechthebbende:</span><span class="content"><?= htmlspecialchars($row['source']); ?></span></div>
                <div><span class="title">Email:</span><span class="content"><?= htmlspecialchars($row['email']); ?></span></div>
                <div><span class="title">Geupload door:</span><span class="content"><?= htmlspecialchars($row['name']); ?></span></div>
                <div><span class="title">IP-adres:</span><span class="content"><?= $row['ip']; ?></span></div>
                <div><span class="title">Geüpload op:</span><span class="content"><?= strftime("%e %B %Y om %H:%I:%S", $row['timestamp']) ?></span></div>
                <div><span class="title">Key</span><span class="content"><?= $row['key']; ?></span></div>
                <div><span class="title">Beschrijving:</span><span class="content"><?= htmlspecialchars($row['description']);?></span></div>
            </div>

            <h3>Wat doen we ermee?</h3>

            <ul class="list">
                <li><a href="//commons.wikimedia.org/wiki/Special:Upload?&uploadformstyle=basicwp&wpUploadFileURL=https://wikidate.nl/wikiportret/uploads/<?= $row['filename']; ?>&wpUploadDescription={{Information%0A|Description={{nl|1=<?= $row['title'] ?>}}%0A|Source=wikiportret.nl%0A|Permission=CC-BY-SA 4.0%0A|Date=<?= $row['date']; ?>%0A|Author=<?= $row['source']; ?>%0A}}%0A{{wikiportrait|}}" target="_blank">Uploaden naar Commons!</a></li>
                <?php
                $results = DB::query('SELECT * FROM messages');

                foreach($results as $row):
                    ?>
                <li><a href="message.php?message=<?= $row['id']; ?>&image=<?= $_GET['id'] ?>"><?= htmlspecialchars($row['title']); ?></a></li>
                <?php
                endforeach;
                ?>
            </ul>
        </div>

        <div class="single-box options">
            <h3>Opties</h3>

            <form method="post" id="owner" name="owner">
                <div class="input-container">
                    <label for="owner"><i class="fa fa-user-md fa-lg fa-fw"></i>Eigenaar</label>

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

                <div class="input-container">
                    <label for="done"><i class="fa fa-check fa-lg fa-fw"></i>Afgehandeld</label>
                    <div class="checkbox">
                        <input type="checkbox" name="done" id="done" <?php if ($owner['archived'] == 1) { echo "checked"; } ?> /><label for="done">Ja</label>
                    </div>
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