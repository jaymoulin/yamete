<?php

namespace YameteTests\Driver;


class MangaHentaiMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
