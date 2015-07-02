<?php
    require '../common/bootstrap.php';

    // Check for old v1 url's, and if so, redirect to old installation
    if (!empty($_GET['id'] && !empty($_GET['secret'])) && defined('V1_PATH')) {
        $url = sprintf(V1_PATH . '/admin/?id=%s&secret=%s', $_GET['id'], $_GET['secret']);
        header("Location:$url");
    }

    if (!empty($_GET['logout'])) {
        $session->logout();
    }

    if (isset($_POST['postback'])) {
        isrequired('username', 'gebruikersnaam');
        isrequired('password', 'wachtwoord');

        if (!hasvalidationerrors()) {
            if ($session->login($_POST['username'], $_POST['password'])) {
                $session->redirect("/index");
            } else {
                addvalidationerror("Gebruikersnaam of wachtwoord is verkeerd");
            }
        }
    }

    if ($session->isLoggedIn()) {
        $session->redirect("/images/overview");
    }

    require '../common/header.php';
?>

<div id="content">
    <h2>Inloggen</h2>
    <p>Hier kunnen medewerkers van Wikiportret inloggen in het beheergedeelte.</p>

    <?php
        if (hasvalidationerrors()) {
            showvalidationsummary();
        }
    ?>

    <form method="post">
        <div class="input-container">
            <label for="username"><i class="fa fa-user fa-lg fa-fw"></i>Gebruikersnaam</label>
            <input type="text" name="username" id="username" autocorrect="off"/>
        </div>

        <div class="input-container">
            <label for="password"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord</label>
            <input type="password" name="password" id="password" />
        </div>

        <div class="bottom right">
            <button class="green" type="submit" name="postback"><i class="fa fa-sign-in fa-lg"></i>Inloggen</button>
        </div>
    </form>
</div>

<?php
    include 'common/footer.php';
?>
