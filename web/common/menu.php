<?php if ($session->isLoggedIn()) : ?>
    <ul>
            <li><a href="<?= $basispad; ?>/images/overview.php"><i class="fa fa-folder-open"></i>
                    <span>Inzendingen</span></a></li>
            <?php if ($session->isSysop()) : ?>
        <li><a href="<?= $basispad; ?>/admin/users.php"><i class="fa fa-wrench"></i><span>Beheer</span></a></li>
            <?php endif; ?>
            <li style='color: rgba(255, 255, 255, 0.5)'><?php echo "(in " . Date('F') . " geanalyseerd: " .
            countGVRequests() . ")";
            ?></li>
        </ul>

    <ul class="right">
            <li><a href="<?= $basispad; ?>/admin/edituser.php?id=<?= $_SESSION['user']; ?>"><i class="fa fa-user"></i>
                    <span><?php echo $session->getUserName(); ?></span></a></li>
                    <li><a href="<?= $basispad; ?>/admin/?logout=1"><i class="fa fa-sign-out"></i>
                                <span>Uitloggen</span></a></li>
        </ul>
<?php else : ?>
&nbsp;
<?php endif; ?>
