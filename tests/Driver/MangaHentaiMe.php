<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaHentaiMe extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangahentai.me/manga-hentai/the-secret-club/';
        $driver = new \Yamete\Driver\MangaHentaiMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(23, count($driver->getDownloadables()));
    }
}
