<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaKakalot extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangakakalot.com/read-ia6ir158504862497';
        $driver = new \Yamete\Driver\MangaKakalot();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(855, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadChapter()
    {
        $url = 'https://mangakakalot.com/chapter/kawaiikereba_hentai_demo_suki_ni_natte_kuremasu_ka/chapter_8';
        $driver = new \Yamete\Driver\MangaKakalot();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
