<?php if ($session->isLoggedIn()) : ?>
    <ul>
        <li><a href="<?= $basispad; ?>/images/overview.php"><i class="fa-solid fa-folder-open"></i><span>Inzendingen</span></a></li>
        <?php if ($session->isSysop()) : ?>
            <li><a href="<?= $basispad; ?>/admin/users.php"><i class="fa-solid fa-wrench"></i><span>Beheer</span></a></li>
        <?php endif; ?>
        <li style='color: rgba(255, 255, 255, 0.5)'><?php echo "(in " . date('F')  . " geanalyseerd: " . countGVRequests() . ")"; ?></li>
    </ul>

    <ul class="right">
        <li><a href="<?= $basispad; ?>/admin/edituser.php?id=<?= $_SESSION['user']; ?>"><i class="fa-solid fa-user"></i><span><?php echo $session->getUserName(); ?></span></a></li>
        <li><a href="<?= $basispad; ?>/admin/?logout=1"><i class="fa-solid fa-sign-out"></i><span>Uitloggen</span></a></li>
    </ul>
<?php else : ?>
    &nbsp;
<?php endif; ?>