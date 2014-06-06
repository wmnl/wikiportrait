<?php 
    include 'header.php';
    checkLogin();
?>
<div id="content">
    <h2>Kant-en-klaar berichtje versturen</h2>

    <?php
        //query
    ?>
    <pre><?php echo $row['message']; ?></pre>
</div>
<?php
    include 'footer.php';
?>