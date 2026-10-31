<?php
header('HTTP/1.1 404 Not Found');
header('Status: 404 Not Found');
require_once 'common/bootstrap.php';
require_once 'common/header.php';
?>
<div id="content">

    <h2><?= t('notfound.title'); ?></h2>

    <p><?= t('notfound.text1'); ?></p>
    <p><?= t('notfound.text2'); ?></p>

</div>
<?php
include 'common/footer.php';
?>