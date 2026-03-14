<?php

// phpcs:disable PSR1.Files.SideEffects
// ini_set('display_errors', 0);
// error_reporting(0);

// header('Content-Type: application/json; charset=utf-8');

define('COMMONSAPI', 'https://commons.wikimedia.org/w/api.php');
define('CATEGORYNS', 14);

$categories = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING, FILTER_FORCE_ARRAY);
$cats = [];
foreach ($categories as $cat) {
    $cat_exists = (key(checkIfCategoryExists($cat)) !== -1);
    $cats[] = [$cat => $cat_exists];
    $altCatsResult = findAlternativeCategories($cat);
}

foreach ($altCatsResult['search'] as $searchresult) {
    $cats[] = [$searchresult['title'] => true];
}
echo json_encode($cats, JSON_UNESCAPED_UNICODE);
exit;

function checkIfCategoryExists($cat)
{
    $params = [
        "action" => "query",
        "format" => "json",
        "titles" => "Category:" . $cat,
        "prop" => "pageprops"
    ];
    $url = COMMONSAPI . "?" . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Belangrijk: User-Agent
    curl_setopt($ch, CURLOPT_USERAGENT, 'Wikiportret/1.0 (https://wikiportret.nl; mbch331.wikipedia@gmail.com)');

    $output = curl_exec($ch);

    $result = json_decode($output, true);
    return $result["query"]["pages"];
}

function findAlternativeCategories($cat)
{
    $params = [
        "action" => "query",
        "format" => "json",
        "srsearch" => strtolower($cat),
        "list" => "search",
        "srnamespace" => CATEGORYNS
    ];
    $url = COMMONSAPI . "?" . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Belangrijk: User-Agent
    curl_setopt($ch, CURLOPT_USERAGENT, 'Wikiportret/1.0 (https://wikiportret.nl; mbch331.wikipedia@gmail.com)');

    $output = curl_exec($ch);
    $result = json_decode($output, true);
    return $result['query'];
}
