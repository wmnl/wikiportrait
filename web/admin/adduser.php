<?php
    require '../common/bootstrap.php';
    require '../common/header.php';
    $session->checkAdmin();
    include 'tabs.php';

    if (isset($_POST['postback'])) {
        $admin = isset($_POST['admin']);

        isrequired('username', 'gebruikersnaam');
        isrequired('otrsname', 'OTRS-naam');
        isrequired('password', 'wachtwoord');
        checkusername($_POST['username']);
        comparepassword($_POST['password'], $_POST['password2']);
        validateEmail('email');

        if (!hasvalidationerrors()) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            DB::insert('users', [
                'username' => $_POST['username'],
                'password' => $password,
                'otrsname' => $_POST['otrsname'],
                'email' => $_POST['email'],
                'isSysop' => $admin,
                'active' => 1
            ]);

            $session->redirect("/admin/users");
        }
    }
?>
<div id="content">

    <div class="page-header">
        <h2>Gebruiker toevoegen</h2>
        <a href="users.php" class="button red"><i class="fa fa-times-circle fa-lg"></i><span>Annuleren</span></a>
    </div>

    <?php
        if (hasvalidationerrors()) {
            showvalidationsummary();
        }
    ?>

    <form method="post">
        <div class="input-container">
        <label for="username"><i class="fa fa-user fa-lg fa-fw"></i>Gebruikersnaam</label>
        <input type="text" name="username" id="username" value="<?php if (isset($_POST['username'])) echo $_POST['username'] ?>"/>
        </div>

        <div class="input-container">
        <label for="otrsname"><i class="fa fa-briefcase fa-lg fa-fw"></i>OTRS-naam</label>
        <input type="text" name="otrsname" id="otrsname" <?php if (isset($_POST['otrsname'])) echo $_POST['otrsname'] ?> />
        </div>

        <div class="input-container">
        <label for="password"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord</label>
        <input type="password" name="password" id="password" />
        </div>

        <div class="input-container">
        <label for="password2"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord nogmaals</label>
        <input type="password" name="password2" id="password2" />
        </div>

        <div class="input-container">
        <label for="email"><i class="fa fa-envelope fa-lg fa-fw"></i>E-mailadres</label>
        <input type="email" name="email" id="email" <?php if (isset($_POST['email'])) echo $_POST['email'] ?> />
        </div>

        <div class="input-container">
        <label for="admin"><i class="fa fa-user-md fa-lg fa-fw"></i>Beheerder</label>
        <div class="checkbox">
            <input type="checkbox" name="admin" id="admin" /><label for="admin">Ja</label>
        </div>
        </div>

        <div class="bottom right">
        <button class="green" type="submit" name="postback"><i class="fa fa-plus fa-lg"></i>Toevoegen</button>
        </div>
    </form>
</div>
<?php
    include '../common/footer.php';
?>
