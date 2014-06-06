<?php
    include '../header.php';
    checkAdmin();
?>            
    <div id="content">
        <h2>Berichtenbeheer</h2>
        <ul>
            <li><a href="adduser.php">Bericht  toevoegen</a></li>
        </ul>

        <table width="95%" border="1px solid black">
            <tr>
                <th width="15%">Titel</th>
                <th width="85%">Bericht</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php
                $query = "SELECT * FROM messages";
                $result = mysql_query($query);
                while ($row = mysql_fetch_assoc($result))
                {
                    $id = $row['id'];
                    $title = $row['title'];
                    $message = $row['message'];

                    echo "<tr>";
                        echo "<td>$title</td>";
                        echo "<td>" . str_replace("\n", "<br />", $message) . "</td>";
                        echo "<td><a href=\"editmessage.php?id=$id\"><img src=\"../images/modify.png\" title=\"Bewerken\" width=\"20px\" /></a></td>";
                        echo "<td><a href=\"deletemessage.php?id=$id\"><img src=\"../images/delete.png\" title=\"Verwijderen\" width=\"20px\" /></a></td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
<?php
    include '../footer.php';
?>
