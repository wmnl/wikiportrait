<?php 
    include 'header.php';
    checkLogin();
?>            
<div id="content">
    <h2>Ingestuurde foto's</h2>

    <a href="archived.php">Archief</a>

    <table border="1px">
        <tr>
            <th width="20%">Afbeelding</th>
            <th width="80%">Info</th>
        </tr>
        <?php
            setlocale(LC_ALL, 'nl_NL');
            if (isset($_GET['page'])) { $page  = $_GET['page']; } else { $page = 1; }; 
            $start_from = ($page-1) * 20;
            $query = "SELECT * FROM images WHERE archived = 1 LIMIT $start_from, 20";
            echo $query;
            $result = mysql_query($query);

            while ($row = mysql_fetch_assoc($result))
            {
                $id = $row['id'];
                $filename = $row['filename'];
                $title = $row['title'];
                $timestamp = $row['timestamp'];
        ?>

        <tr>
            <td><a href="image.php?id=<?php echo $id ?>"><img src="uploads/<?php echo $filename?>" width="300px" /></a></td>
            <td>
                <ul>
                    <li>Titel: <?php echo $title ?></li>
                    <li>Geüpload: <?php echo gmdate("d F Y - H:i:s", $timestamp) ?></li>
                    <li><a href="image.php?id=<?php echo $id ?>">Bekijk alle details</a></li>
                </ul>
            </td>
        </tr>

        <?php
            }
        ?>
    </table>

    Ga naar pagina:
    <?php
        $query = "SELECT COUNT(*) FROM images WHERE archived = 1";
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        $total_records = $row[0];

        $total_pages = ceil($total_records / 20);

        for ($i=1; $i<=$total_pages; $i++) { 
            echo "<a href='images.php?page=".$i."'>".$i."</a> "; 
        }; 
    ?>

</div>
<?php
    include 'footer.php';
?>