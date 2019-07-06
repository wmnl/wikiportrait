<?php
require '../common/bootstrap.php';

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

$imageAnnotator = new ImageAnnotatorClient();

$fileName = 'uploads/images/dfs-1560015237.png';

$image = file_get_contents($fileName);

# performs label detection on the image file
$response = $imageAnnotator->labelDetection($image);
$labels = $response->getLabelAnnotations();

if ($labels) {
    echo("Labels:" . PHP_EOL);
    foreach ($labels as $label) {
        echo($label->getDescription() . PHP_EOL);
    }
} else {
    echo('No label found' . PHP_EOL);
}
