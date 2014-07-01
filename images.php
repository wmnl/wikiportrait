<?php
	include 'header.php';
	checkLogin();
        if (isset ($_GET['archived']) && $_GET['archived'] == 1)
        {
            $archived = 1;
        }
        else
        {
            $archived = 0;
        }
?>		
<div id="content">
	<h2>Ingestuurde foto's</h2>

	<?php
                if ($archived == 0)
                {
                    echo "<a href=\"images.php?archived=1\" class=\"button float-right\"><i class=\"fa fa-archive fa-lg fa-fw\"></i>Archief</a>";
                }
                else
                {
                    echo "<a href=\"images.php?archived=0\" class=\"button float-right\"><i class=\"fa fa-archive fa-lg fa-fw\"></i>Archief</a>";
                }
		?>

	<table>
            <thead>
                <tr>
                    <th class="center" style="width:10em;">Foto</th>
                    <th>Titel</th>
                    <th>Uploader</th>
                    <th>Datum</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    setlocale(LC_ALL, 'nl_NL');
                    date_default_timezone_set('Europe/Amsterdam');
                    if (isset($_GET['page'])) { $page  = $_GET['page']; } else { $page = 1; }; 
                    $start_from = ($page-1) * 20;
                    $query = sprintf("SELECT * FROM images WHERE archived = $archived ORDER BY id DESC LIMIT %d, 20", mysql_real_escape_string($start_from));
                    echo "<div class=\"succes\">" . $query . "</div>";
                    $result = mysql_query($query);

                    while ($row = mysql_fetch_assoc($result))
                    {
                        $id = $row['id'];
                        $filename = $row['filename'];
                        $title = $row['title'];
                        $name = $row['name'];
                        $timestamp = $row['timestamp'];
                ?>

                <tr>
                    <td><a href="image.php?id=<?php echo $id ?>"><img src="uploads/<?php echo $filename?>" style="width:7.5em; margin:auto;" /></a></td>
                    <td><a href="image.php?id=<?php echo $id ?>"><?php echo $title ?></a></td>
                    <td><?php echo $name ?></td>
                    <td><?php echo strftime("%e %B %Y om %H:%I:%S", $timestamp) ?></td>
                </tr>

                <?php
                    }
                ?>
            </tbody>
	</table>
	<div class="float-right" style="margin-top:10px;">Ga naar pagina:
		<?php
                    $query = "SELECT COUNT(*) FROM images WHERE archived = $archived";
                    $result = mysql_query($query);
                    $row = mysql_fetch_row($result);
                    $total_records = $row[0];

                    $total_pages = ceil($total_records / 20);

                    if ($total_pages > 10)
                    {
                            for ($i=1; $i<=5; $i++) 
                            {
                                    echo "<a href=\"images.php?page=$i&archived=$archived\" class=\"button\" style=\"margin-left:5px;\">".$i."</a>"; 
                            }
                            echo "<i class=\"button\" style=\"margin-left:5px;\">...</i>"; 
                            for ($i=$total_pages - 4; $i <= $total_pages; $i++) 
                            {
                                    echo "<a href=\"images.php?page=$i&archived=$archived\" class=\"button\" style=\"margin-left:5px;\">".$i."</a>"; 
                            }
                    }
                    else
                    {
                            for ($i=1; $i<=$total_pages; $i++) 
                            { 
                                    echo "<a href=\"images.php?page=$i&archived=$archived\" class=\"button\" style=\"margin-left:5px;\">".$i."</a>"; 
                            }
                    }
                ?>
	</div>
	<div class="clear"></div>
</div>
<?php
    include 'footer.php';
?>
