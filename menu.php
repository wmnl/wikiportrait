<?php
    $basispad = "/wikiportret";
?>
<ul>
    <li><a href="/wikiportret/index.php">Home</a></li>
    <li><a href="/wikiportret/upload.php">Insturen</a></li>
    <?php
        if(isset($_SESSION['user']))
        {
            echo "<li><a href=\"$basispad/images.php\">Inzendingen</a></li>";
            
            if($_SESSION['isSysop'] == true)
                echo "\n\t<li><a href=\"$basispad/admin\">Beheer</a></li>";
        }
    ?>  
    <li><?php if (isset($_SESSION['user'])) echo "<a href=\"$basispad/logout.php\">Uitloggen</a>"; else echo "<a href=\"$basispad/login.php\">Inloggen</a>"; ?></li>
</ul>   