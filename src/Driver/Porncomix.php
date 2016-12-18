<?php
namespace FemtoPixel\Parser\Driver;

$url = "http://www.porncomix.info/week-with-peg-goof-troop/";
$domain = "porncomix.info";
$folder = "week-with-peg-goof-troop";
$path = __DIR__ . DIRECTORY_SEPARATOR . $domain . DIRECTORY_SEPARATOR . $folder;
if (!file_exists($path)) {
    mkdir($path, 0644, true);
}
$client = new \GuzzleHttp\Client();
$res = $client->request('GET', $url);
$body = (string)$res->getBody();

$dom = new \PHPHtmlParser\Dom;
$dom->load($body);
$contents = $dom->find('#gallery-1 dt a');
foreach($contents as $link) {
    /** @var \DOMElement $link */
    $res = (string)$client->request('GET', $link->getAttribute('href'))->getBody();
    $dom->load($res);
    /** @var \DOMElement $img */
    $img = $dom->find('.single-post .attachment-image img');
    $src = $img->getAttribute('src');
    file_put_contents($path . DIRECTORY_SEPARATOR . basename($src), file_get_contents($src));
}
