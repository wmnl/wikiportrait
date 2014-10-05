<?php
$page = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);

echo "<ul>\n";

	echo "\t\t\t\t\t<li" .(($page == 'index')?' class="active"':""). "><a href=\"$basispad/index.php\" title=\"Home\"><i class=\"fa fa-home\"></i><span>Home</span></a></li>\n";
	echo "\t\t\t\t\t<li" .(($page == 'wizard')?' class="active"':""). "><a href=\"$basispad/wizard.php\" title=\"Insturen\"><i class=\"fa fa-cloud-upload\"></i><span>Insturen</span></a></li>\n";
	
	if(isset($_SESSION['user']))
	{
		echo "\t\t\t\t\t<li" .(($page == 'upload')?' class="active"':""). "><a href=\"$basispad/upload.php\" title=\"Uploaden\"><i class=\"fa fa-upload\"></i><span>Uploaden</span></a></li>\n";
		echo "\t\t\t\t\t<li" .(($page == 'overview')?' class="active"':""). "><a href=\"$basispad/images/overview.php\" title=\"Inzendingen\"><i class=\"fa fa-folder-open\"></i><span>Inzendingen</span></a></li>\n";
	}
	
	if($_SESSION['isSysop'] == true)
	{
		echo "\t\t\t\t\t<li" .(($page == 'users')?' class="active"':""). "><a href=\"$basispad/admin/users.php\" title=\"Beheer\"><i class=\"fa fa-wrench\"></i><span>Beheer</span></a></li>\n";
	}
	
	echo "\t\t\t\t\t<li" . (($page == 'login')?' class="active"':"") . ">";
	
		if (isset($_SESSION['user']))
		{
			echo "<a href=\"$basispad/logout.php\" title=\"Uitloggen\"><i class=\"fa fa-sign-out\"></i><span>Uitloggen</span></a>";
		}
		else
		{
			echo "<a href=\"$basispad/login.php\" title=\"Inloggen\"><i class=\"fa fa-sign-in\"></i><span>Inloggen</span></a>";
		}
	
	echo "</li>\n";

echo "\t\t\t\t</ul>\n";
?>