<?php
    include 'header.php';
?>
<div id="content">
    <h2>Inzending volgen</h2>
    <?php
        if (!isset($_GET['image']) || !isset($_GET['key']))
        {
            echo "<div class=\"error\">Geen afbeelding gevonden!</div>";
        }
        else
        {
            $query = sprintf("SELECT * FROM images WHERE id = %d", mysql_real_escape_string($_GET['image']));
            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            if (mysql_num_rows($result) == 0)
            {
                echo "<div class=\"error\">Geen afbeelding gevonden!</div>";
            }
            
            
    ?>
        <h3>Kriesant</h3>
        <div class="error">
            Status:
            Nog niet in behandeling
        </div>
    
    <a href="uploads/kriesant-1399810386.jpg" target="_blank" style="margin:-25px;"><img class="detail" src="uploads/kriesant-1399810386.jpg"/></a>

    <ul>
        <li>Titel: Kriesant</li>
        <li>Auteur: Windhoos</li>
        <li>Naam uploader: Jurgen</li>
        <li>Geüpload op: 11 May 2014 12:13:06</li>
        <li>Beschrijving: </li>
    </ul>
    
    <br clear="all" />
    <?php
        }
    ?>
</div>
<?php
    include 'footer.php';
?>