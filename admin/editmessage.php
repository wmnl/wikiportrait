<?php
    require '../common/bootstrap.php';
    $session->checkAdmin();

    if (isset($_GET['id'])) {
	   $id = $_GET['id'];
    } else {
	   $session->redirect("/admin/users");
    }

    $row = DB::query('SELECT * FROM messages WHERE id = %d', $id);
    $row = $row[0];

    if (DB::count() == 0) {
        $session->redirect("/admin/messages");
    }

    if (isset($_POST['postback'])) {
        isrequired('title', 'titel');
        isrequired('message', 'bericht');

        if (!hasvalidationerrors()) {
            DB::update('messages', [
                'title' => $_POST['title'],
                'message' => $_POST['message']
            ], 'id = %d', $_GET['id']);

            $session->redirect("/admin/messages");
        }
    }

    require '../common/header.php';
    include 'tabs.php';
?>
<div id="content">
    <?php
        if (hasvalidationerrors()) showvalidationsummary();
    ?>

    <div class="page-header">
    	<h2>Bericht bewerken</h2>
        <a href="messages.php" class="button red"><i class="fa fa-ban fa-lg"></i><span>Annuleren</span></a>
    </div>

    <form method="post">
	    <div class="input-container">
		<label for="title"><i class="fa fa-tag fa-lg fa-fw"></i>Titel</label>
		<input type="text" name="title" id="title" value="<?php echo htmlspecialchars($row['title']); ?>" required="required" />
	    </div>

	    <div class="input-container">
		<label for="message"><i class="fa fa-align-left fa-lg fa-fw"></i>Bericht</label>
		<textarea required="required" name="message" ><?php echo htmlspecialchars($row['message']); ?></textarea>
	    </div>

	    <div class="bottom right">
		<button class="green" name="postback"><i class="fa fa-floppy-o fa-lg"></i>Opslaan</button>
	    </div>
    </form>
</div>
<?php
    include '../common/footer.php';
?>