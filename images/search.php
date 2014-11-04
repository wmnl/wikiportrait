<?php
include '../header.php';
include 'tabs.php';
checkLogin();
?>

<div id="content">

	<div class="page-header">
		<h2>Zoeken</h2>
	</div>
	<?
	if (!empty($errors))
		{
			echo "<div class=\"box red\"><ul>";

			foreach ($errors as $error)
			{
				echo "<li>$error</li>";
			}

			echo "</ul></div>";
		}
		else
		{
			if (isset($_POST['postback']))
			{
				$errors = array();

			if (empty ($_POST['search']))
			{
				array_push($errors, "Er is geen zoekterm ingevoerd");
			}

			if (empty($errors))
			{
				$query = sprintf("SELECT * FROM images WHERE title LIKE '%%%s%%'", mysqli_real_escape_string($connection, $_POST['search']));
				$result = mysqli_query($connection, $query);

				if (mysqli_num_rows($result) > 0)
				{
				?>
				
	<div class="table-container">

		<table>
				<thead>
					<tr>
						<th>Foto</th>
						<th>Titel</th>
						<th>Uploader</th>
						<th>Datum</th>
						<th>Eigenaar</th>
						<th class="actions-1">Acties</th>
					</tr>
				</thead>
				
				<tbody>
				<?php
					while ($row = mysqli_fetch_assoc($result)):
						$id = $row['id'];
						$filename = $row['filename'];
						$title = $row['title'];
						$name = $row['name'];
						$timestamp = $row['timestamp'];	
				?>
					<tr>
					
						<td data-title="Foto" class="image"><a href="single.php?id=<?php echo $id ?>"><img src="../uploads/<?php echo $filename?>" /></a></td>
						<td data-title="Titel"><a href="single.php?id=<?php echo $id ?>"><?php echo $title ?></a></td>
						<td data-title="Uploader"><?php echo $name ?></td>
						<td data-title="Datum"><?php echo strftime("%e %B %Y", $timestamp) ?></td>
						<td data-title="Eigenaar"><?= $owner ?></td>
						<td data-title="Acties" class="center"><a class="button" href="single.php?id=<?php echo $id ?>"><i class="fa fa-info"></i>Details</a></td>
					</tr>
					<?php
					 endwhile;
					?>
				</tbody>
		</table>
		<?php
				
				}
				else
				{
					echo "<div class=\"box red\">Er zijn geen resultaten gevonden.</div>";
				}
			}
			else
			{
			echo "<div class=\"box red\"><ul>";

			foreach ($errors as $error)
			{
				echo "<li>$error</li>";
			}

			echo "</ul></div>";
			}
		}
	}
	?>
	
		<form method="post">
		
		<div class="input-container">
			<label for="search"><i class="fa fa-search"></i>Zoekterm</label>
			<input type="text" name="search" id="search" />
		</div>

		<div class="bottom right">
			<button class="green" type="submit" name="postback"><i class="fa fa-search"></i>Zoeken</button>
		</div>

	</form>
	
</div>
<?php
include '../footer.php';
?>
