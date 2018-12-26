<?php

namespace YameteTests\Driver;


class MangaFap extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://mangafap.com/read-oideyo-mizuryu-kei-land-the-5th-day-english-doujinshi-online/';
        $driver = new \Yamete\Driver\MangaFap();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(39, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadSpecial()
    {
        $url = 'http://mangafap.com/read-147-centimeter-french-hentai-manga-online/';
        $driver = new \Yamete\Driver\MangaFap();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
