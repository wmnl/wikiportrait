<?php
// phpcs:disable PSR1.Files.SideEffects

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
echo json_encode($cats);

function checkIfCategoryExists($cat)
{
    $params = [
    "action" => "query",
    "format" => "json",
    "titles" => "Category:" . $cat,
        "prop" => "pageprops"
    ];
    $url = COMMONSAPI . "?" . http_build_query($params);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
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

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($output, true);
    return $result['query'];
}
