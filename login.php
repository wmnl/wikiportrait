<?php
    if (isset($_SESSION['user'])) {
        header("Location:index.php");
    }

    require 'common/header.php';
    require_once 'common/formfunctions.php';
?>

<div id="content">
    <h2>Inloggen</h2>
    <p>Hier kunnen medewerkers van Wikiportret inloggen in het beheergedeelte.</p>

    <?php
    if (isset($_POST['postback'])) {
        isrequired('username', 'gebruikersnaam');
        isrequired('password', 'wachtwoord');

        if (hasvalidationerrors()) {
            showvalidationsummary();
        } else {
            $row = DB::queryFirstRow("SELECT * FROM users WHERE username = %s AND active = 1", $_POST['username']);

            if (DB::count() != 0) {
                if (password_verify($_POST['password'], $row['password'])) {
                    $_SESSION['user'] = $row['id'];
                    $_SESSION['isSysop'] = $row['isSysop'] == 1;
                    header("Location:index.php");
                } else {
                    echo '<div class="box red">Wachtwoord incorrect.</div>';
                }
            } else {
                echo '<div class="box red">Gebruikersnaam onbekend.</div>';
            }
        }
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
