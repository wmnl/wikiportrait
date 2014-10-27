<?php
$page = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
?>
<div class="tabs tabs-images">
	<ul>
		<li<?php if (!isset($_GET['user']) && !isset($_GET['archived']) && $page != 'search') echo " class='active'" ?>><a href="overview.php"><i class="fa fa-picture-o"></i><span>Inzendingen</span></a></li>
		<li<?php if (isset ($_GET['archived'])) echo " class='active'" ?>><a href="overview.php?archived=1"><i class="fa fa-archive"></i><span>Archief</span></a></li>
		<li<?php if (isset ($_GET['personal'])) echo " class='active'"?>><a href="overview.php?personal"><i class="fa fa-tachometer"></i><span>Persoonlijk overzicht</span></a></li>
		<li<?php if ($page == 'search') echo " class='active'"?>><a href="search.php"><i class="fa fa-search"></i><span>Zoeken</span></a></li>
	</ul>
</div>
