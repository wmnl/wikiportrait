<?php $page2 = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME); ?>
<ul class="tabs">
	<li<?php if ($page2 == 'users') echo ' class="active"'?>><a href="users.php"><i class="fa fa-users"></i>Gebruikers</a></li>
	<li<?php if ($page2 == 'messages') echo ' class="active"'?>><a href="messages.php"><i class="fa fa-comments"></i>Berichten</a></li>
	<div class="clear"></div>
</ul>