<?php
	require '../config/connect.php';   
	include '../header.php';
	include 'tabs.php';
	checkAdmin();
	if (isset($_GET['id']))
	{
	$id = $_GET['id'];
	}
	else 
	{
	header("Location: users.php");
	}
?>			
<div id="content">

	<div class="page-header">

		<h2>Bericht bewerken</h2>

		<a href="messages.php" class="button red"><i class="fa fa-ban fa-lg"></i><span>Annuleren</span></a>

	</div>
	
	<?php
	$query = sprintf("SELECT * FROM messages WHERE id = %d", mysqli_real_escape_string($connection, $id)); 
	$result = mysqli_query($connection, $query);
	$row = mysqli_fetch_assoc($result);
	
	if (mysqli_num_rows($result) == 0)
	{
		 echo "<div class=\"box red\">Bericht niet gevonden!</div>";
	}
	else
	{
		if (isset($_POST['postback']))
		{
		    $errors = array();

		    $title = $_POST['title'];
		    $message = $_POST['message'];

		    if (empty ($title))
		    {
			      array_push($errors, "Er is geen titel ingevuld");
		    }
		    if (empty($message))
		    {
			      array_push($errors, "Er is geen bericht ingevuld");
		    }

		    if (count($errors) == 0)
		    {
			      $query = sprintf("UPDATE messages SET title = '%s', message= '%s' WHERE id = $id", mysqli_real_escape_string($connection, $title), mysqli_real_escape_string($connection, $message));

			      mysqli_query($connection, $query);
			      header("Location:messages.php");
		    }
		    else
		    {
			echo "<div class=\"box red\"><ul>";
			foreach ($errors as $error)
			{
			    echo "<li>$error</li>";
			}
			echo "</div>";
		    }
		}
	?>
	<form method="post">
		<div class="input-container">
			<label for="title"><i class="fa fa-tag fa-lg fa-fw"></i>Titel</label>
			<input type="text" name="title" id="title" value="<?php echo $row['title']; ?>"	 required="required"/>
		</div>

		<div class="input-container">
			<label for="message"><i class="fa fa-align-left fa-lg fa-fw"></i>Bericht</label>
			<textarea required="required" name="message" ><?php echo $row['message'] ?></textarea>
		</div>

		<div class="bottom right">
				<button class="green" name="postback"><i class="fa fa-floppy-o fa-lg"></i>Opslaan</button>
		</div>
	</form>
</div>
<?php
	}
	include '../footer.php';
?>