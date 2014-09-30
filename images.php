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
	<h2>Ingestuurde foto's</h2>

	<?php
		 if ($archived == 0)
		 {
		echo "<a href=\"images.php?archived=1\" class=\"button float-right\"><i class=\"fa fa-archive fa-lg fa-fw\"></i>Archief</a>";
		 }
		 else
		 {
		echo "<a href=\"images.php?archived=0\" class=\"button float-right\"><i class=\"fa fa-archive fa-lg fa-fw\"></i>Archief</a>";
		 }
	?>

	<table>
			<thead>
				<tr>
					<th class="center" style="width:10em;">Foto</th>
					<th>Titel</th>
					<th>Uploader</th>
					<th>Datum</th>
				</tr>
			</thead>
			<tbody>
				<?php
					setlocale(LC_ALL, 'nl_NL');
					date_default_timezone_set('Europe/Amsterdam');
					if (isset($_GET['page'])) { $page  = $_GET['page']; } else { $page = 1; }; 
					$start_from = ($page-1) * 20;
					$query = sprintf("SELECT * FROM images WHERE archived = $archived ORDER BY id DESC LIMIT %d, 20", mysqli_real_escape_string($connection, $start_from));
					echo "<div class=\"box grey\">" . $query . "</div>";
					$result = mysqli_query($connection, $query);

					while ($row = mysqli_fetch_assoc($result)):
						$id = $row['id'];
						$filename = $row['filename'];
						$title = $row['title'];
						$name = $row['name'];
						$timestamp = $row['timestamp'];
				?>

				<tr>
					<td><a href="image.php?id=<?php echo $id ?>"><img src="uploads/<?php echo $filename?>" style="width:7.5em; margin:auto;" /></a></td>
					<td><a href="image.php?id=<?php echo $id ?>"><?php echo $title ?></a></td>
					<td><?php echo $name ?></td>
					<td><?php echo strftime("%e %B %Y om %H:%I:%S", $timestamp) ?></td>
				</tr>

				<?php
			  endwhile;
				?>
			</tbody>
	</table>
	
	<form method="post" class="bottom float-right" style="margin-top:10px; width:20%;">
		
		<div class="input-container">
		
			<label for="page" style="width:70%;">Ga naar pagina</label>
			
			<select name="page" onchange="loadPage()" id="page">	
				<?php
					$query = "SELECT COUNT(*) FROM images WHERE archived = $archived";
					$result = mysqli_query($connection, $query);
					$row = mysqli_fetch_row($result);
					$total_records = $row[0];
	
				$total_pages = ceil($total_records / 20);
				for ($i=1; $i<=$total_pages; $i++) :
				  ?>
				<option value='<?= $i ?>' <? if ($_GET['page'] == $i) echo 'selected' ?>><?= $i ?></option>";
				  <?
				endfor;
				?>
			</select>
			
		</div>
		
	</form>
	
	<div class="clear"></div>
	
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
