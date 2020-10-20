<?php

function makeGVResults($results_array)
{
    $list = "<ul class='gv_results' id='matching_pages'>";
    foreach ($results_array as $key => $page) {
        $list .= "<li><a href='$page' target='_blank'>". substr($page, 0, 50) . "..." ."</a></li>";
    }
    $list .= "</ul>";

    return $list;
}

function countGVRequests()
{
    $num = DB::queryFirstField(
        "SELECT COUNT(*) FROM vision_api_results WHERE vision_api_results.date > %s and vision_api_results.date < %s",
        Date('Y-m-01'),
        Date('Y-m-t')
    );

    return $num;
}

function activeGVRequests()
{
    $num = countGVRequests();

    if ($num < GV_REQUESTS_LIMIT) {
        return true;
    } else {
        return false;
    }
}
