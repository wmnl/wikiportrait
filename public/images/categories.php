<?php

// phpcs:disable PSR1.Files.SideEffects
require_once '../common/bootstrap.php';
$session->checkLogin();

header('Content-Type: application/json; charset=utf-8');

define('COMMONSAPI', 'https://commons.wikimedia.org/w/api.php');
define('CATEGORYNS', 14);

// Names are escaped where they are put in the page, so only keep non-empty strings here
$categories = array_filter((array) ($_POST['cat'] ?? []), fn($cat) => is_string($cat) && trim($cat) !== '');
$cats = [];
foreach ($categories as $cat) {
    $pages = checkIfCategoryExists($cat);
    $cat_exists = $pages && key($pages) !== -1;
    $cats[] = [$cat => $cat_exists];
    foreach (findAlternativeCategories($cat)['search'] ?? [] as $searchresult) {
        $cats[] = [$searchresult['title'] => true];
    }
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
    return $result["query"]["pages"] ?? [];
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
    return $result['query'] ?? [];
}
