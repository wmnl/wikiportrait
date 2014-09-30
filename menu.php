<?php $page = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME); ?>
<ul>
    <li<?php if ($page == 'index') echo ' class="active"'?>><a href="<?php echo $basispad; ?>/index.php" title="Home"><i class="fa fa-home"></i><span>Home</span></a></li>
    <li<?php if ($page == 'wizard') echo ' class="active"'?>><a href="<?php echo $basispad; ?>/wizard.php" title="Insturen"><i class="fa fa-cloud-upload"></i><span>Insturen</span></a></li>
    <li<?php if ($page == 'upload') echo ' class="active"'?>><a href="<?php echo $basispad; ?>/upload.php" title="Uploaden"><i class="fa fa-upload"></i><span>Uploaden</span></a></li>
    <?php
        if(isset($_SESSION['user']))
            {
                echo "<li" .(($page == 'images')?' class="active"':""). " ><a href=\"$basispad/images.php\" title=\"Inzendingen\"><i class=\"fa fa-folder-open\"></i><span>Inzendingen</span></a></li>";

                if($_SESSION['isSysop'] == true)
                    echo "\n\t<li" .(($page == 'users')?' class="active"':""). "><a href=\"$basispad/admin/users.php\" title=\"Beheer\"><i class=\"fa fa-wrench\"></i><span>Beheer</span></a></li>";
            }
    ?>  
    <li<?php if ($page == 'login') echo ' class="active"'?>><?php if (isset($_SESSION['user'])) echo "<a href=\"$basispad/logout.php\" title=\"Uitloggen\"><i class=\"fa fa-sign-out\"></i><span>Uitloggen</span></a>"; else echo "<a href=\"$basispad/login.php\" title=\"Inloggen\"><i class=\"fa fa-sign-in\"></i><span>Inloggen</span></a>"; ?></li>
    <div class="clear"></div>
</ul>