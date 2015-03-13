<?php if ($session->isLoggedIn()): ?>
    <ul>
        <li>
            <a href="<?= $basispad; ?>/images/overview.php">
                <i class="fa fa-folder-open"></i>Inzendingen
            </a>
        </li>

        <?php if ($session->isSysop()) : ?>
        <li>
            <a href="<?= $basispad; ?>/admin/users.php">
                <i class="fa fa-wrench"></i>Beheer
            </a>
        </li>
        <?php endif; ?>

        <li>
            <a href="<?= $basispad; ?>/logout.php">
                <i class="fa fa-sign-out"></i>Uitloggen
            </a>
        </li>
    </ul>

    <ul class="right">
        <li>
            <span>
                <i class="fa fa-user"></i>
                <?php echo $session->getUserName(); ?>
            </span>
        </li>
    </ul>
<?php endif; ?>