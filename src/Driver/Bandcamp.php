<?php
require_once 'vendor/autoload.php';

$url = "https://volaband.bandcamp.com/album/inmazes";
$domain = "bandcamp.com";
$folder = "volaband" . DIRECTORY_SEPARATOR . 'inmazes';
$path = __DIR__ . DIRECTORY_SEPARATOR . $domain . DIRECTORY_SEPARATOR . $folder;
if (!file_exists($path)) {
    mkdir($path, 0644, true);
}
$client = new GuzzleHttp\Client();
$res = $client->request('GET', $url);
$body = (string)$res->getBody();

preg_match('~trackinfo: (\[.+?\]),~s', $body, $result);
foreach(json_decode($result[1], true) as $content) {
    $src = $content['file']['mp3-128'];
    $name = $content['title'];
    file_put_contents($path . DIRECTORY_SEPARATOR . $name . '.mp3', file_get_contents('https:' . $src));
}
