<?php
require '../common/config.php';
require ABSPATH . '/common/bootstrap.php';

$session->checkLogin();

//Receive the RAW post data.
$content = trim(file_get_contents("php://input"));
//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($content, true);

if (!empty($decoded)) {

if ( activeGVRequests() ) {

  $row = DB::queryFirstRow('SELECT filename FROM images WHERE id = %i', $decoded['id']);

  detect_web($row['filename']);

  $google_vision_results = DB::queryFirstRow("SELECT vision_api_results.date, labels, description, matching_pages,
  matching_img, similar_img, partial_pages FROM vision_api_results WHERE image_id = %i ORDER BY id DESC", $decoded['id']);

if (!empty($google_vision_results['description'])) {
  $matching_pages = explode(',', $google_vision_results['matching_pages']);
  (!empty($google_vision_results['matching_pages'])) ? $mathing_p_count = count($matching_pages) : $mathing_p_count = 0;
  $matching_img = explode(',', $google_vision_results['matching_img']);
  (!empty($google_vision_results['matching_img'])) ? $mathing_i_count = count($matching_img) : $mathing_i_count = 0;
  $similar_img = explode(',', $google_vision_results['similar_img']);
  (!empty($google_vision_results['similar_img'])) ? $similar_i_count = count($similar_img) : $similar_i_count = 0;
  $partial_img = explode(',', $google_vision_results['partial_img']) ;
  (!empty($google_vision_results['partial_img'])) ? $partialcount = count($partial_img) : $partialcount = 0;

  echo "<ul class='list_ml'>
   <li><span>Datum analyse:</span> ". $google_vision_results['date'] ." <i class='performgv' onclick=\"performGVResults('". $decoded['id'] . "')\">Nog een keer analyseren</i></li>
   <li><span>Beste categorie:</span> ". $google_vision_results['labels'] . "</li>
   <li><span>Beschrijving:</span> ". $google_vision_results['description'] ."</li>";

    if ($mathing_p_count > 0) {
      echo "<li class='matching_pages'><span>Aantal webpaginas met dezelfde afbeelding:</span> $mathing_p_count <i class='toon' onclick='showGVResults(this)'>tonen</i>";
      echo makeGVResults($matching_pages);
      echo "</li>";
    }
    if ($similar_i_count > 0) {
      echo "<li class='similar_img'><span>Aantal vergelijkbare afbeeldingen:</span> $similar_i_count <i class='toon' onclick='showGVResults(this)'>tonen</i>";
      echo makeGVResults($similar_img);
      echo "</li>";
    }
    if ($partialcount > 0) {
      echo "<li class='partial_img'><span>Aantal gedeeltelijke afbeeldingen:</span> $partialcount <i class='toon' onclick='showGVResults(this)'>tonen</i>";
      echo makeGVResults($partial_img);
      echo "</li>";
    }
    if ($mathing_i_count > 0) {
      echo "<li class='matching_img'><span>Aantal gedeeltelijke afbeeldingen:</span> $mathing_i_count <i class='toon' onclick='showGVResults(this)'>tonen</i>";
      echo makeGVResults($matching_img);
      echo "</li></ul>";
      }
    } else { // Analysis results are empty....
      echo "<span>Geen resultaten</span> <i class='performgv' onclick=\"performGVResults('". $decoded['id'] . "')\">Nog een keer analyseren</i>";
    }
} else { // Analysis results are empty....
  echo "Niet geactiveerd";
}

}
