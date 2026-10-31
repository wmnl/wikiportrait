<?php

require_once '../common/config.php';
require_once ABSPATH . '/common/bootstrap.php';

$session->checkLogin();

//Receive the RAW post data.
$content = trim(file_get_contents("php://input"));
//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($content, true);

if (!empty($decoded)) {
    $id  = (int) ($decoded['id'] ?? 0);
    $row = DB::queryFirstRow('SELECT filename FROM images WHERE id = %i', $id);

    if (!$row) {
        echo t('single.not_found');
    } elseif (activeGVRequests()) {
        detect_web($row['filename']);

        $google_vision_results = DB::queryFirstRow("SELECT vision_api_results.date, labels, description, matching_pages,
  matching_img, similar_img, partial_pages FROM vision_api_results WHERE image_id = %i ORDER BY id DESC", $id);

        $analyseAgain = t('gv.analyse_again');
        $show = t('js.show');
        if (!empty($google_vision_results['description'])) {
            $matching_pages = explode(',', $google_vision_results['matching_pages']);
            (!empty($google_vision_results['matching_pages'])) ? $mathing_p_count = count($matching_pages) : $mathing_p_count = 0;
            $matching_img = explode(',', $google_vision_results['matching_img']);
            (!empty($google_vision_results['matching_img'])) ? $mathing_i_count = count($matching_img) : $mathing_i_count = 0;
            $similar_img = explode(',', $google_vision_results['similar_img']);
            (!empty($google_vision_results['similar_img'])) ? $similar_i_count = count($similar_img) : $similar_i_count = 0;
            $partial_img = explode(',', $google_vision_results['partial_img'] ?? '');
            (!empty($google_vision_results['partial_img'])) ? $partialcount = count($partial_img) : $partialcount = 0;

            echo "<ul class='list_ml'>
   <li><span>" . t('gv.date') . "</span> " . htmlspecialchars($google_vision_results['date']) . " <i class='performgv' onclick=\"performGVResults('" . $id . "')\">$analyseAgain</i></li>
   <li><span>" . t('gv.best_label') . "</span> " . htmlspecialchars($google_vision_results['labels']) . "</li>
   <li><span>" . t('gv.description') . "</span> " . htmlspecialchars($google_vision_results['description']) . "</li>";

            if ($mathing_p_count > 0) {
                echo "<li class='matching_pages'><span>" . t('gv.matching_pages') . ":</span> $mathing_p_count <i class='toon' onclick='showGVResults(this)'>$show</i>";
                echo makeGVResults($matching_pages);
                echo "</li>";
            }
            if ($similar_i_count > 0) {
                echo "<li class='similar_img'><span>" . t('gv.similar_img') . ":</span> $similar_i_count <i class='toon' onclick='showGVResults(this)'>$show</i>";
                echo makeGVResults($similar_img);
                echo "</li>";
            }
            if ($partialcount > 0) {
                echo "<li class='partial_img'><span>" . t('gv.partial_img') . ":</span> $partialcount <i class='toon' onclick='showGVResults(this)'>$show</i>";
                echo makeGVResults($partial_img);
                echo "</li>";
            }
            if ($mathing_i_count > 0) {
                echo "<li class='matching_img'><span>" . t('gv.matching_img') . ":</span> $mathing_i_count <i class='toon' onclick='showGVResults(this)'>$show</i>";
                echo makeGVResults($matching_img);
                echo "</li></ul>";
            }
        } else { // Analysis results are empty....
            echo "<span>" . t('gv.no_results') . "</span> <i class='performgv' onclick=\"performGVResults('" . $id . "')\">$analyseAgain</i>";
        }
    } else { // Analysis results are empty....
        echo t('gv.not_activated');
    }
}
