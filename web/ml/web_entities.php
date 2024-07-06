<?php

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . GOOGLE_CREDENTIALS);

// $imgname = "dfs-1560015237.png";

function detect_web($name)
{

    $path = ABSPATH . "/uploads/images/";
    $image = file_get_contents($path . $name);

    if (filesize($path . $name) > 10000000) { // 10MB
      // echo filesize($path . $name);
    } else {
        $imageAnnotator = new ImageAnnotatorClient();
        $response = $imageAnnotator->webDetection($image);
        $web = $response->getWebDetection();
        $labels = [];
        $matching_pages = [];
        $matching_images = [];
        $similar_images = [];
        $partial_pages = [];
        $description = [];

    // get labels:
        foreach ($web->getBestGuessLabels() as $label) {
            array_push($labels, $label->getLabel());
        }
    // get pages with matching images
        foreach ($web->getPagesWithMatchingImages() as $page) {
            array_push($matching_pages, $page->getUrl());
        }
    // get full matching images
        foreach ($web->getFullMatchingImages() as $fullMatchingImage) {
            array_push($matching_images, $fullMatchingImage->getUrl());
        }
    // get partial matching pages
        foreach ($web->getPartialMatchingImages() as $partialMatchingImage) {
            array_push($partial_pages, $partialMatchingImage->getUrl());
        }
    // get visually similar images
        foreach ($web->getVisuallySimilarImages() as $visuallySimilarImage) {
            array_push($similar_images, $visuallySimilarImage->getUrl());
        }
    // get web entities
        foreach ($web->getWebEntities() as $entity) {
            if ($entity->getDescription() != "") {
                $entity_formatted = $entity->getDescription() . " (" . round($entity->getScore(), 2) . ")";
                array_push($description, $entity_formatted);
            }
        }

    // store ML analysis
        $imagequery = DB::queryFirstRow('SELECT id FROM images WHERE filename = %s', $name);
        if (DB::count() > 0) {
            $image_id = $imagequery['id'];
        }

        if (isset($image_id)) {
            DB::insert('vision_api_results', [
            'image_id' => $image_id,
            'date' => date('Y-m-d H:i:s'),
            'labels' => implode(',', $labels),
            'description' => implode(',', $description),
            'matching_pages' => implode(',', $matching_pages),
            'matching_img' => implode(',', $matching_images),
            'similar_img' => implode(',', $similar_images),
            'partial_pages' => implode(',', $partial_pages)
            ]);
        }

        $imageAnnotator->close();
    }
}

// detect_web($imgname);
