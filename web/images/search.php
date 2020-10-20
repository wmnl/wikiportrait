<?php
    require '../common/bootstrap.php';
    $session->checkLogin();
    require '../common/header.php';

    include 'tabs.php';
?>

<div id="content">
    <div class="page-header">
    <h2>Zoeken</h2>
    </div>

    <form method="post" class="search-form">
    <div class="input-container">
        <label for="search"><i class="fa fa-search"></i>Zoekterm</label>
        <input type="text" name="search" id="search" />
    </div>

    <div class="bottom right">
        <button class="green" type="submit" name="postback"><i class="fa fa-search"></i>Zoeken</button>
    </div>
    </form>

    <?php
    if (!empty($errors)) {
        echo "<div class=\"box red\">";

        foreach ($errors as $error) {
            echo "$error";
        }

        echo "</div>";
    } else {
        if (isset($_POST['postback'])) {
            $errors = array();

            if (empty($_POST['search'])) {
                array_push($errors, "Er is geen zoekterm ingevoerd");
            }

            if (empty($errors)) {
                $results = DB::query("SELECT users.otrsname AS owneruser FROM images INNER JOIN users ON images.owner =
        users.id WHERE title LIKE '%%%s%%'", $_POST['search']);

                if (DB::count() > 0) {
                    ?>

    <div class="table-container">
        <table>
        <thead>
            <tr>
            <th>Foto</th>
            <th>Titel</th>
            <th>Uploader</th>
            <th>Datum</th>
            <th>Eigenaar</th>
            <th class="actions-1">Acties</th>
            </tr>
        </thead>

        <tbody>
                    <?php
                    foreach ($results as $row) :
                        $id = $row['id'];
                        $filename = htmlspecialchars($row['filename']);
                        $title = htmlspecialchars($row['title']);
                        $name = htmlspecialchars($row['name']);
                        $owner = htmlspecialchars($row['owneruser']);
                        $timestamp = $row['timestamp'];
                        ?>
            <tr>
                <td data-title="&#xf03e;" class="image"><a href="single.php?id=<?php echo $id ?>"><img
                            src="../uploads/thumbs/<?php echo $filename ?>" /></a></td>
                <td data-title="&#xf02b;"><a href="single.php?id=<?php echo $id ?>"><?php echo $title ?></a></td>
            <td data-title="&#xf007;"><?php echo $name ?></td>
            <td data-title="&#xf073;"><?php echo strftime("%e %B %Y", $timestamp) ?></td>
            <td data-title="&#xf0f0;"><?= $owner ?></td>
            <td data-title="&#xf0ae;" class="center"><a class="button" href="single.php?id=<?php echo $id ?>"><i
                        class="fa fa-info"></i>Details</a></td>
            </tr>
                        <?php
                    endforeach;
                    ?>
        </tbody>
        </table>

                    <?php
                } else {
                    echo "<div class=\"box red\">Er zijn geen resultaten gevonden.</div>";
                }
            } else {
                echo "<div class=\"box red\"><ul>";

                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }

                echo "</ul></div>";
            }
        }
    }
    ?>

    </div>
</div>

<?php
    include '../common/footer.php';
?>
