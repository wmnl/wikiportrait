<?php
    require '../common/bootstrap.php';
    $session->checkAdmin();

    if (isset($_GET['id'])) {
		$id = $_GET['id'];
    } else {
		$session->redirect("/admin/users");
    }

    require '../common/header.php';
    include 'tabs.php';
?>
<div id="content">
    <div class="page-header">
	<h2>Gebruiker bewerken</h2>
	<a href="users.php" class="button red"><i class="fa fa-ban fa-lg"></i><span>Annuleren</span></a>
    </div>

    <?php
	$row = DB::queryFirstRow('SELECT * FROM users WHERE id = %d', $_GET['id']);

	if (DB::count() == 0) {
	    echo "Gebruiker niet gevonden!";
	} else {
	    if (isset($_POST['postback'])) {
			$admin = isset($_POST['admin']);
			$active = isset($_POST['active']);

			isrequired('username', 'gebruikersnaam');
			isrequired('otrsname', 'OTRS-naam');
			comparepassword($_POST['password'], $_POST['password2']);
			validateEmail('email');

			if (!hasvalidationerrors()) {
			    if (empty($_POST['password'])) {
					DB::update('users', array(
					    'username' => $_POST['username'],
					    'otrsname' => $_POST['otrsname'],
					    'email' => $_POST['email'],
					    'isSysop' => $admin,
					    'active' => $active
					), 'id = %d', $_GET['id']);

					header ("Location: users.php");
			    } else {
			    	$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

					DB::update('users', array(
					    'username' => $_POST['username'],
					    'password' => $password,
					    'otrsname' => $_POST['otrsname'],
					    'email' => $_POST['email'],
					    'isSysop' => $admin,
					    'active' => $active
					), 'id = %d', $_GET['id']);

					header ("Location: users.php");
			    }
			}

			if (hasvalidationerrors()) {
			    showvalidationsummary();
			}
	    }
	}
    ?>

    <form method="post">
	<div class="input-container">
	    <label for="username"><i class="fa fa-user fa-lg fa-fw"></i>Gebruikersnaam</label>
	    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($row['username']); ?>" />
	</div>

	<div class="input-container">
	    <label for="otrsname"><i class="fa fa-briefcase fa-lg fa-fw"></i>OTRS-naam</label>
	    <input type="text" name="otrsname" id="otrsname" value="<?php echo htmlspecialchars($row['otrsname']); ?>" />
	</div>

	<div class="input-container">
	    <label for="password"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord</label>
	    <input type="password" name="password" id="password" placeholder="Enkel invullen als u het wachtwoord wilt wijzigen"/>
	</div>

	<div class="input-container">
	    <label for="password2"><i class="fa fa-key fa-lg fa-fw"></i>Wachtwoord nogmaals</label>
	    <input type="password" name="password2" id="password2" placeholder="Enkel invullen als u het wachtwoord wilt wijzigen" />
	</div>

	<div class="input-container">
	    <label for="email"><i class="fa fa-envelope fa-lg fa-fw"></i>E-mailadres</label>
	    <input type="email" name="email" id="email" value="<?php echo $row['email']; ?>"/>
	</div>

	<div class="input-container">
	    <label for="admin"><i class="fa fa-user-md fa-lg fa-fw"></i>Beheerder</label>
	    <div class="checkbox">
		<input type="checkbox" name="admin" id="admin" <?php if ($row['isSysop'] == 1) echo "checked"; ?> /><label for="admin">Ja</label>
	    </div>
	</div>

	<div class="input-container">
	    <label for="active"><i class="fa fa-power-off fa-lg fa-fw"></i>Geactiveerd</label>
	    <div class="checkbox">
		<input type="checkbox" name="active" id="active" <?php if ($row['active'] == 1) echo "checked"; ?> /><label for="active">Ja</label>
	    </div>
	</div>

	<div class="bottom right">
	    <button class="green" name="postback"><i class="fa fa-floppy-o fa-lg"></i>Opslaan</button>
	</div>

    </form>
</div>
<?php
    include '../common/footer.php';
?>