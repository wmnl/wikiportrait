<?php
	include '../header.php';
	include 'tabs.php';
	checkLogin();
	if (isset ($_GET['archived']) && $_GET['archived'] == 1)
	{
		$archived = 1;
	}
	else
	{
		$archived = 0;
	}
?>
<div id="content">

	<div class="page-header">

		<?php
			if ($archived == 0)
				echo "<h2>Inzendingen</h2>";
			if ($archived == 1)
				echo "<h2>Archief</h2>";
		?>

		<form class="navigation" method="post">

				<label for="page">Pagina</label>

				<select class="select" name="page" onchange="loadPage()" id="page">
					<?php
					$query = "SELECT COUNT(*) FROM images WHERE archived = $archived";
					$result = mysqli_query($connection, $query);
					$row = mysqli_fetch_row($result);
					$total_records = $row[0];

					$total_pages = ceil($total_records / 10);
					for ($i=1; $i<=$total_pages; $i++) :
					?>
					<option value='<?= $i ?>' <? if ($_GET['page'] == $i) echo 'selected' ?>><?= $i ?></option>";
					<?
					endfor;
					?>
				</select>

		</form>

	</div>

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
						setlocale(LC_ALL, 'nl_NL');
						date_default_timezone_set('Europe/Amsterdam');
						if (isset($_GET['page'])) { $page	= $_GET['page']; } else { $page = 1; };
						$start_from = ($page-1) * 10;
						$query = sprintf("SELECT images.id as image_id, images.title as title, images.filename as filename, images.name as name, images.timestamp as timestamp, users.otrsname as otrsname FROM images LEFT JOIN users ON users.id = owner WHERE archived = $archived ORDER BY images.id DESC LIMIT %d, 10", mysqli_real_escape_string($connection, $start_from));

						$result = mysqli_query($connection, $query);

						while ($row = mysqli_fetch_assoc($result)):
							$id = $row['image_id'];
							$filename = $row['filename'];
							$title = $row['title'];
							$name = $row['name'];
							$timestamp = $row['timestamp'];
							if (empty($row['otrsname']))
							{
							    $owner = "Aan niemand toegewezen";
							}
							else
							{
							    $owner = $row['otrsname'];
							}
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

	</div>

</div>
<script>
	function getUrlParameter(sParam) {
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++)
	{
		 var sParameterName = sURLVariables[i].split('=');
		 if (sParameterName[0] == sParam)
		 {
		return sParameterName[1];
		 }
	}
	}

	var page = getUrlParameter('page');
	var archived = getUrlParameter('archived');

	if (page == undefined) {
	page = 1;
	}

	if (archived == undefined) {
	archived = 0;
	}

	function loadPage() {
	window.location.href = 'overview.php?page=' + document.getElementById('page').value + '&archived=' + archived;
	}
</script>
<?php
	include '../footer.php';
?>
