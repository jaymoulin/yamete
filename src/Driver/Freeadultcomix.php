<?php
$url = "http://freeadultcomix.com/double-date-hoshi-furry-comix/";
$domain = "freeadultcomix.com";
$folder = "double-date-hoshi-furry-comix";
$path = __DIR__ . DIRECTORY_SEPARATOR . $domain . DIRECTORY_SEPARATOR . $folder;
if (!file_exists($path)) {
    mkdir($path, 0644, true);
}
$client = new GuzzleHttp\Client();
$res = $client->request('GET', $url);
$body = (string)$res->getBody();

$dom = new PHPHtmlParser\Dom;
$dom->load($body);
$contents = $dom->find('.single-post p img');
foreach ($contents as $img) {
    /** @var DOMElement $img */
    $src = $img->getAttribute('src');
    file_put_contents($path . DIRECTORY_SEPARATOR . basename($src), file_get_contents('http://freeadultcomix.com/' . $src));
}
