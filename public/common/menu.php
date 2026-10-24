<?php if ($session->isLoggedIn()) : ?>
<ul>
    <li><a href="<?= $basispad; ?>/images/overview.php"><i
                class="fa-solid fa-folder-open"></i><span><?= t('menu.submissions'); ?></span></a></li>
    <?php if ($session->isSysop()) : ?>
    <li><a href="<?= $basispad; ?>/admin/users.php"><i class="fa-solid fa-wrench"></i><span><?= t('menu.admin'); ?></span></a></li>
    <?php endif; ?>
    <li style='color: rgba(255, 255, 255, 0.5)'>
    <?php
    echo t('menu.analysed', ['month' => formatDate(time(), 'date.format_month'), 'count' => countGVRequests()]);
    ?>
    </li>
</ul>

<ul class="right">
    <li><a href="<?= $basispad; ?>/admin/edituser.php?id=
    <?=
    $_SESSION['user'];
    ?>
                                                                "><i
                class="fa-solid fa-user"></i><span><?php echo $session->getUserName(); ?></span></a></li>
    <li><a href="<?= $basispad; ?>/admin/?logout=1"><i lass="fa-solid fa-sign-out"></i><span><?= t('menu.logout'); ?></span></a></li>
</ul>
<?php else : ?>
&nbsp;
<?php endif; ?>