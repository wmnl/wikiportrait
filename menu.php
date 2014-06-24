<?php $page = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME); ?>
<ul>
    <li<?php if ($page == 'index') echo ' class="active"'?>><a href="<?php echo $basispad; ?>/index.php"><i class="fa fa-home fa-lg"></i><span>Home</span></a></li>
    <li<?php if ($page == 'wizard') echo ' class="active"'?>><a href="<?php echo $basispad; ?>/wizard.php"><i class="fa fa-cloud-upload fa-lg"></i><span>Insturen</span></a></li>
    <li<?php if ($page == 'upload') echo ' class="active"'?>><a href="<?php echo $basispad; ?>/upload.php"><i class="fa fa-upload fa-lg"></i><span>Uploaden</span></a></li>
    <div class="float-right">
    <?php
        if(isset($_SESSION['user']))
            {
                echo "<li" .(($page == 'images')?'class="active"':""). " ><a href=\"$basispad/images.php\"><i class=\"fa fa-folder-open fa-lg\"></i><span>Inzendingen</span></a></li>";

                if($_SESSION['isSysop'] == true)
                    echo "\n\t<li" .(($page == 'admin')?'class="active"':"").  "><a href=\"$basispad/admin\"><i class=\"fa fa-wrench fa-lg\"></i><span>Beheer</span></a></li>";
            }
    ?>  
    <li<?php if ($page == 'index') echo ' class="active"'?>><?php if (isset($_SESSION['user'])) echo "<a href=\"$basispad/logout.php\"><i class=\"fa fa-sign-out fa-lg\"></i><span>Uitloggen</span></a>"; else echo "<a href=\"$basispad/login.php\"><i class=\"fa fa-sign-in fa-lg\"></i><span>Inloggen</span></a>"; ?></li>
    </div>
</ul>