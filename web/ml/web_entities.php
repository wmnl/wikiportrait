<?php
namespace Google\Cloud\Samples\Vision;

ini_set('display_errors', 1);

require '../common/bootstrap.php';

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . GOOGLE_CREDENTIALS);

$imgpath = "ABSPATH . '/uploads/images/";
$imgname = "dfs-1560015237.png";

$img = $imgpath . $imgname;

function detect_web($path)
{
    $imageAnnotator = new ImageAnnotatorClient();

    # annotate the image
    $image = file_get_contents($path);
    $response = $imageAnnotator->webDetection($image);
    $web = $response->getWebDetection();

    // store ML analysis
    DB::update('images', [
    'ml_analysis' => 'JSON_OBJECT()',
    ], 'filename = %s', $imgname);

    printf('%d best guess labels found' . "<br><br>",
        count($web->getBestGuessLabels()));
    foreach ($web->getBestGuessLabels() as $label) {
        printf('Best guess label: %s' . "<br><br>", $label->getLabel());
    }
    print(PHP_EOL);

    // Print pages with matching images
    printf('%d pages with matching images found' . "<br><br>",
        count($web->getPagesWithMatchingImages()));
    foreach ($web->getPagesWithMatchingImages() as $page) {
        printf('URL: %s' . "<br><br>", $page->getUrl());
    }
    print("<br><br>");

    // Print full matching images
    printf('%d full matching images found' . "<br><br>",
        count($web->getFullMatchingImages()));
    foreach ($web->getFullMatchingImages() as $fullMatchingImage) {
        printf('URL: %s' . "<br><br>", $fullMatchingImage->getUrl());
    }
    print("<br><br>");

    // Print partial matching images
    printf('%d partial matching images found' . "<br><br>",
        count($web->getPartialMatchingImages()));
    foreach ($web->getPartialMatchingImages() as $partialMatchingImage) {
        printf('URL: %s' . PHP_EOL, $partialMatchingImage->getUrl());
    }
    print("<br><br>");

    // Print visually similar images
    printf('%d visually similar images found' . "<br><br>",
        count($web->getVisuallySimilarImages()));
    foreach ($web->getVisuallySimilarImages() as $visuallySimilarImage) {
        printf('URL: %s' . "<br><br>", $visuallySimilarImage->getUrl());
    }
    print("<br><br>");

    // Print web entities
    printf('%d web entities found' . "<br><br>",
        count($web->getWebEntities()));
    foreach ($web->getWebEntities() as $entity) {
        printf('Description: %s, Score %s' . "<br>",
            $entity->getDescription(),
            $entity->getScore());
    }

    $imageAnnotator->close();
}

detect_web($img);
