<?php 
    include 'header.php';
    checkLogin();
?>
<div id="content">
    <h2>Kant-en-klaar berichtje versturen</h2>

    <?php
    
        if (!isset($_GET['image']) || !isset($_GET['message']))
        {
            echo "<div class=\"error\"><ul>";
            echo "<li>Bericht of afbeelding niet gevonden!</li>";
            echo "</ul></div>";
        }
        else
        {
    
    $query = sprintf("SELECT * FROM messages
              LEFT JOIN users
              ON users.id = '%s'
              LEFT JOIN images
              ON images.id = '%s'
              WHERE messages.id = '%s'", mysql_real_escape_string($_SESSION['user']), mysql_real_escape_string($_GET['image']), mysql_real_escape_string($_GET['message']));
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    
    $templates = array("%%name%%", "%%title%%", "%%otrsname%%");
    $replace = array($row['name'], $row['title'], $row['otrsname']);
    ?>
    <textarea>
        <?php 
            echo str_replace($templates, $replace, $row['message']); 
        ?>
    </textarea>
    <?php
        }
    ?>
</div>
<?php
    include 'footer.php';        
?>