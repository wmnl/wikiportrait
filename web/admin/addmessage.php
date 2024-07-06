<?php
    require '../common/bootstrap.php';
    require '../common/header.php';
    $session->checkAdmin();
    include 'tabs.php';

if (isset($_POST['postback'])) {
    isrequired('title', 'titel');
    isrequired('message', 'bericht');

    if (!hasvalidationerrors()) {
        DB::insert('messages', [
          'title' => $_POST['title'],
          'message' => $_POST['message']
        ]);

        $session->redirect("/admin/messages");
    }
}
?>
<div id="content">

    <div class="page-header">

    <h2>Bericht toevoegen</h2>

    <a href="messages.php" class="button red"><i class="fa fa-times-circle fa-lg"></i><span>Annuleren</span></a>

    </div>

    <?php
    if (hasvalidationerrors()) {
        showvalidationsummary();
    }
    ?>

    <form method="post">

    <div class="input-container">
        <label for="title"><i class="fa fa-tag fa-lg fa-fw"></i>Titel</label>
        <input type="text" name="title" id="title" value="
        <?php
        if (isset($_POST['title'])) {
            echo $_POST['title'];
        }
        ?>
        " required="required"/>
    </div>

    <div class="input-container">
        <label for="message"><i class="fa fa-align-left fa-lg fa-fw"></i>Bericht</label>
        <textarea required="required" name="message">
        <?php
        if (isset($_POST['message'])) {
            echo $_POST['message'];
        }
        ?>
        </textarea>
    </div>

    <div class="bottom right">
        <button class="green" type="submit" name="postback"><i class="fa fa-plus fa-lg"></i>Toevoegen</button>
    </div>

    </form>

</div>
<?php
    include '../common/footer.php';
?>
