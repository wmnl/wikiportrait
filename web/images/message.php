<?php
require '../common/bootstrap.php';
$session->checkLogin();
require '../common/header.php';

?>
<div id="content">
    <h2>Kant-en-klaar berichtje versturen</h2>

    <div class="input-container">
        <label for="message"><i class="fa fa-align-left fa-lg fa-fw"></i>Bericht</label>
        <?php
        if (!isset($_GET['image']) || !isset($_GET['message'])) {
            echo "<div class=\"box red\">Bericht of afbeelding niet gevonden!</div>";
        } else {
            $row = DB::queryFirstRow(
                'SELECT * FROM messages LEFT JOIN users ON users.id = %d LEFT JOIN images '
                . 'ON images.id = %d WHERE messages.id = %d',
                $_SESSION['user'],
                $_GET['image'],
                $_GET['message']
            );

            $templates = array("%%name%%", "%%title%%", "%%otrsname%%");
            $replace = array($row['name'], $row['title'], $row['otrsname']);
            $newTxt=str_replace($templates, $replace, $row['message']);
            ?>
                <textarea name="message" onclick="this.focus();this.select()"><?php echo $newTxt; ?></textarea>
            <?php
        }

        ?>
    </div>
</div>
<?php
include '../common/footer.php';
