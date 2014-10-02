<?php
	include 'header.php';
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

		<h2>Ingestuurde foto's</h2>
	
		<?php
			 if ($archived == 0)
			 {
			echo "<a href=\"images.php?archived=1\" class=\"button\" title=\"Archief\"><i class=\"fa fa-archive fa-lg fa-fw\"></i><span>Archief</span></a>";
			 }
			 else
			 {
			echo "<a href=\"images.php?archived=0\" class=\"button\" title=\"Archief\"><i class=\"fa fa-archive fa-lg fa-fw\"></i><span>Archief</span></a>";
			 }
		?>
	
		<form class="navigation" method="post">
				
				<label for="page">Pagina</label>
				
				<select class="select" name="page" onchange="loadPage()" id="page">	
					<?php
						$query = "SELECT COUNT(*) FROM images WHERE archived = $archived";
						$result = mysqli_query($connection, $query);
						$row = mysqli_fetch_row($result);
						$total_records = $row[0];
		
					$total_pages = ceil($total_records / 15);
					for ($i=1; $i<=$total_pages; $i++) :
					  ?>
					<option value='<?= $i ?>' <? if ($_GET['page'] == $i) echo 'selected' ?>><?= $i ?></option>";
					  <?
					endfor;
					?>
				</select>
	
		</form>
		
		<div class="clear"></div>
	
	</div>

	<table>
			<thead>
				<tr>
					<th class="icon center">Foto</th>
					<th>Titel</th>
					<th>Uploader</th>
					<th>Datum</th>
				</tr>
			</thead>
			<tbody>
				<?php
					setlocale(LC_ALL, 'nl_NL');
					date_default_timezone_set('Europe/Amsterdam');
					if (isset($_GET['page'])) { $page	= $_GET['page']; } else { $page = 1; }; 
					$start_from = ($page-1) * 15;
					$query = sprintf("SELECT * FROM images WHERE archived = $archived ORDER BY id DESC LIMIT %d, 15", mysqli_real_escape_string($connection, $start_from));
					$result = mysqli_query($connection, $query);

					while ($row = mysqli_fetch_assoc($result)):
						$id = $row['id'];
						$filename = $row['filename'];
						$title = $row['title'];
						$name = $row['name'];
						$timestamp = $row['timestamp'];
				?>

				<tr>
					<td class="image"><a href="image.php?id=<?php echo $id ?>"><img src="uploads/<?php echo $filename?>" /></a></td>
					<td><a href="image.php?id=<?php echo $id ?>"><?php echo $title ?></a></td>
					<td><?php echo $name ?></td>
					<td><?php echo strftime("%e %B %Y", $timestamp) ?></td>
				</tr>

				<?php
				 endwhile;
				?>
			</tbody>
	</table>
	
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
	window.location.href = 'images.php?page=' + document.getElementById('page').value + '&archived=' + archived;
	}
</script>
<?php
	include 'footer.php';
?>
