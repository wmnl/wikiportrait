<?php 
    include 'header.php';
    checkLogin();
?>		
<div id="content">
    <h2>Ingestuurde foto's</h2>

    <a href="archived.php" class="button float-right"><i class="fa fa-archive fa-lg fa-fw"></i>Archief</a>

    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Titel</th>
                <th>Uploader</th>
                <th>Datum</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php
                setlocale(LC_ALL, 'nl_NL');
                if (isset($_GET['page'])) { $page  = $_GET['page']; } else { $page = 1; }; 
                $start_from = ($page-1) * 20;
                $query = "SELECT * FROM images WHERE archived = 0 LIMIT $start_from, 20";
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
                <td><a href="image.php?id=<?php echo $id ?>"><img src="uploads/<?php echo $filename?>" style="width:100%;" /></a></td>
                <td><?php echo $title ?></td>
                <td><?php echo $name ?></td>
                <td><?php echo gmdate("d F Y - H:i:s", $timestamp) ?></td>
                <td><a href="image.php?id=<?php echo $id ?>">Bekijk alle details</a></td>
            </tr>

            <?php
                }
            ?>
            <tr class="navigation">
                <th colspan="5">
                    <div class="float-right">Ga naar pagina:
                        <?php
                            $query = "SELECT COUNT(*) FROM images WHERE archived = 0";
                            $result = mysql_query($query);
                            $row = mysql_fetch_row($result);
                            $total_records = $row[0];

                            $total_pages = ceil($total_records / 20);

                            for ($i=1; $i<=$total_pages; $i++) 
                            { 
                                    echo "<a href=\"images.php?page=".$i."\" class=\"page\">".$i."</a>"; 
                            }; 
                        ?>
                    </div>
                </th>
            </tr>
        </tbody>
    </table>
</div>
<?php
    include 'footer.php';
?>