<?php

function makeGVResults($results_array) {
  $list = "<ul class='gv_results' id='matching_pages'>";
  foreach ($results_array as $key => $page) {
      $list .= "<li><a href='$page' target='_blank'>". substr($page, 0, 50) . "..." ."</a></li>";
  }
  $list .= "</ul>";

return $list;
}
