<?php
    include 'header.php';
?>
<div id="content">
    <?php
        if (!isset($_GET['image']) || !isset($_GET['key']))
        {
            echo "<div class=\"box red\">Geen afbeelding gevonden!</div>";
        }
        else
        {
            $query = sprintf("SELECT * FROM images WHERE id = %d", mysqli_real_escape_string($connection, $_GET['image']));
            $result = mysqli_query($connection, $query);
            $row = mysqli_fetch_assoc($result);
            if (mysqli_num_rows($result) == 0 || sha1($row['id']) != $_GET['key'])
            {
                echo "<div class=\"box red\">Geen afbeelding gevonden!</div>";
            }
            else
            {
            
    ?>
        <h2>Inzending volgen: <?php echo $row['title']; ?> </h2> 
        <?php
            if ($row['archived'] == 1)
            {
                echo "<div class=\"box green\"><i class=\"fa fa-check fa-lg fa-fw\"></i>Afgehandeld</div>";
            }
            elseif ($row['archived'] == 0 && $row['owner'] != 0)
            {
                echo "<div class=\"box grey\"><i class=\"fa fa-clock-o fa-lg fa-fw\"></i>In behandeling</div>";
            }
            elseif ($row['archived'] == 0 && $row['owner'] == 0)
            {
                echo "<div class=\"box grey\"><i class=\"fa fa-clock-o fa-lg fa-fw\"></i>In de wachtrij</div>";
            }
            else
            {
                echo "<div class=\"box red\"><i class=\"fa fa-exclamation-triangle  fa-lg fa-fw\"></i>Status onbekend</div>";
            }
            
        ?>
    
    	<a href="uploads/<?php echo $row['filename']; ?>" target="_blank" class="float-right"><img src="uploads/<?php echo $row['filename'] ;?>" style="max-width:15em;" /></a>
        
	<h3>Informatie</h3>

	<p>
	    <ul>
		    <li>Titel: <?php echo $row['title']; ?></li>
		    <li>Auteur: <?php echo $row['source']; ?></li>
		    <li>Naam uploader: <?php echo $row['name']; ?></li>
		    <li>Beschrijving: <?php echo $row['description'];?></li>
	    </ul>
	</p>
        
        <br clear="all" />
    <?php
            }
        }
    ?>
</div>
<?php
    include 'footer.php';
?>