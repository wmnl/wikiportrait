<?php
    include "connect.php";
    
    if (isset($_POST['postback']))
    {
        $errors = array();
        
        if (empty($_POST['file']))
        {
            array_push($errors, "Er is geen bestand geselecteerd");
        }
        
        if (empty($_POST['depicted'])) 
        {
            array_push($errors, "Er is niet ingevuld wie er op de foto staat");
        }
        
        if (empty($_POST['copyrightholder']))
        {
            array_push($errors, "Er is niet ingevuld wie de auteursrechthebbende is");
        }
        
        if (empty($_POST['name']))
        {
            array_push($errors, "Er is geen naam ingevuld");
        }
        
        if (empty($_POST['email']))
        {
            array_push($errors, "Er is geen e-mailadres ingevuld");
        }
        elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            array_push($errors, "Er is geen geldig e-mailadres ingevuld");
        }
        
        if (empty($errors))
        {
            $file = $_POST['filename'] . " (" . rand(0,10000) . ")";
            $depicted = $_POST['depicted'];
            $copyrightholder = $_POST['copyrightholder'];
            $name = $_POST['name'];
            $email = $_POST['email'];
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="style/style.css" />
        <title>Wikiportret - Stel uw foto's ter beschikking</title>
    </head>
    
    <body>
        <div id="all">
            <div id="header">
                <h1>Wikiportret</h1>
            </div>
            
            <div id="menu">
               <?php include 'menu.php'; ?>
            </div>
            
            <div id="content">
                <h2>Uploadformulier</h2>
                
                <?php
                    if (!empty($errors))
                    {
                        echo "<ul>";
                        
                        foreach ($errors as $error)
                        {
                            echo "<li>" . $error . "</li>";
                        }
                        
                        echo "</ul>";
                    }
                ?>
                
                <form method="post" enctype="multipart/form-data">
                    <p>
                        <label for="file">Bestand dat u wilt uploaden</label>
                        <input type="file" name="file" id="file" required="required" value="<?php echo $_POST['file']; ?>" />
                    </p>
                    
                    <p>
                        <label for="depicted">Wie staat er op de foto?</label>
                        <input type="text" name="depicted" id="depicted" required="required" value="<?php echo $_POST['depicted']; ?>" />
                    </p>
                    
                    <p>
                        <label for="copyrightholder">Wie is de auteursrechthebbende en/of auteur van de foto?</label>
                        <input type="text" name="copyrightholder" id="copyrightholder" required="required" value="<?php echo $_POST['copyrightholder']; ?>" />
                    </p>
                    
                    <p>
                        <label for="name">Uw naam</label>
                        <input type="text" name="name" id="name" required="required" value="<?php echo $_POST['name']; ?>" />
                    </p>
                    
                    <p>
                        <label for="email">Uw e-mailadres</label>
                        <input type="email" id="email" name="email" value="<?php echo $_POST['email']; ?>" />
                    </p>
                    
                    <p>
                        <label for="date">Datum van de foto (optioneel)</label>
                        <input type="date" name="date" id="date" placeholder="YYYY-MM-DD" value="<?php echo $_POST['date']; ?>" />
                    </p>
                    
                    <p>
                        <label for="description">Omschrijving (optioneel)</label>
                        <textarea name="description" id="description"><?php echo $_POST['description']; ?></textarea>
                    </p>
                    
                    <p>
                        <label for="license">Licentie</label>
                        <select id="license" name="license">
                            <optgroup label="Aanbeloven licentie">
                                <option value="cc-by-sa-3.0">Creative Commons Naamsvermelding-Gelijk delen 3.0</option>
                            </optgroup>
                            
                            <optgroup label="Overige licenties">
                                <option value="cc-0">Creative Commons CC0 1.0 Universele Public Domain Dedication</option>
                                <option>Hier nog een</option>
                                <option>En nog een</option>
                            </optgroup>
                        </select>
                    </p>
                    
                    <p>
                        <label>Licentievoorwaarden</label>
                        <textarea readonly="readonly">Door het uploaden van dit materiaal en het klikken op de knop 'Upload foto' verklaart u dat u de rechthebbende eigenaar bent van het materiaal. Door dit materiaal te uploaden geeft u toestemming voor het gebruik van het materiaal onder de condities van de door u geselecteerde licentie(s), deze condities variëren per licentie maar houden in ieder geval in dat het materiaal verspreid, bewerkt en commercieel gebruikt mag worden door eenieder. Voor de specifieke extra condities per licentie verwijzen u naar de bijbehorende licentieteksten. U kunt op het vrijgeven van deze rechten na het akkoord gaan met deze voorwaarden niet meer terugkomen. De Wikimedia Foundation en haar chapters (waaronder de Vereniging Wikimedia Nederland) zijn op geen enkele wijze aansprakelijk voor misbruik van het materiaal of aanspraak op het materiaal door derden. De eventuele geportretteerden hebben geen bezwaar tegen publicatie onder genoemde licenties. Ook uw eventuele opdrachtgever geeft toestemming.</textarea>
                    </p>
                    
                    <p>
                        <label>&nbsp;</label>
                        <input type="submit" name="postback" value="Versturen" />
                    </p>
                    
                </form>
            </div>
        </div>
    </body>
</html>