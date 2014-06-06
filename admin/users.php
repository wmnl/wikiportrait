<?php
    include '../header.php';
    checkAdmin();
?>
<div id="content">

    <h2>Gebruikersbeheer</h2>

    <a href="adduser.php" class="button float-right"><i class="fa fa-plus fa-lg fa-fw"></i>Nieuwe gebruiker</a>
    <div class="succes">Welkom bij het gebruikersbeheer. Kies een gebruiker of maak een nieuwe gebruiker aan.</div>

    <table>
        <thead>
            <tr>
                <th>Gebruikersnaam</th>
                <th>OTRS-naam</th>
                <th>E-mailadres</th>
                <th>Beheerder</th>
                <th>Acties</th>
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
                            echo "<td>$username</td>";
                            echo "<td>$otrsname</td>";
                            echo "<td>$email</td>";
                            if ($sysop) echo "<td><i class=\"fa fa-check-square-o fa-lg\"></i></td>"; else echo "<td><i class=\"fa fa-minus-square-o fa-lg\"></i></td>";
                            echo "<td><a href=\"edituser.php?id=$id\"><i class=\"fa fa-wrench fa-lg\"></i></a><a href=\"deleteuser.php?id=$id\"><i class=\"fa fa-trash-o fa-lg\"></i></a></td>";
                    echo "</tr>";
            }
        ?>
        </tbody>
    </table>
</div>
<?php
    include '../footer.php';
?>