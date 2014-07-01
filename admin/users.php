<?php
    include '../header.php';
    include 'tabs.php';
    checkAdmin();
?>
<div id="content">

    <h2>Gebruikersbeheer</h2>

    <a href="adduser.php" class="button float-right"><i class="fa fa-plus-square fa-lg"></i>Nieuwe gebruiker</a>
    <div class="box grey">Welkom bij het gebruikersbeheer. Kies een gebruiker of maak een nieuwe gebruiker aan.</div>

    <table>
        <thead>
            <tr>
            	<th class="center" style="width:3em;">ID</th>
                <th>Gebruikersnaam</th>
                <th>OTRS-naam</th>
                <th>E-mailadres</th>
                <th class="center" style="width:6em;">Beheerder</th>
                <th class="center" style="width:6em;" colspan="2">Acties</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $query = "SELECT * FROM users";
            $result = mysql_query($query);
            while ($row = mysql_fetch_assoc($result))
            {
                    $id = $row['id'];
                    $username = $row['username'];
                    $otrsname = $row['otrsname'];
                    $email = $row['email'];
                    $sysop = $row['isSysop'];

                    echo "<tr>";
                    	echo "<td class=\"center\">$id</td>";
                        echo "<td>$username</td>";
                        echo "<td>$otrsname</td>";
                        echo "<td>$email</td>";
                        if ($sysop) echo "<td class=\"center\"><i class=\"fa fa-check-square fa-lg\" style=\"color:#339966;\"></i></td>"; else echo "<td class=\"center\"><i class=\"fa fa-minus-square fa-lg\" style=\"color:#990000;\"></i></td>";
                        echo "<td class=\"center\"><a href=\"edituser.php?id=$id\"><i class=\"fa fa-pencil fa-lg\"></i></a></td>";
                        echo "<td class=\"center\"><a href=\"deleteuser.php?id=$id\"><i class=\"fa fa-trash-o fa-lg\"></i></a></td>";
                    echo "</tr>";
            }
        ?>
        </tbody>
    </table>
</div>
<?php
    include '../footer.php';
?>