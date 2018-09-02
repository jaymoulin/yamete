<?php

namespace YameteTests\Driver;


class MangaKakalot extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://mangakakalot.com/manga/kawaiikereba_hentai_demo_suki_ni_natte_kuremasu_ka';
        $driver = new \Yamete\Driver\MangaKakalot();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(274, count($driver->getDownloadables()));
    }

    public function testDownloadChapter()
    {
        $url = 'https://mangakakalot.com/chapter/kawaiikereba_hentai_demo_suki_ni_natte_kuremasu_ka/chapter_8';
        $driver = new \Yamete\Driver\MangaKakalot();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
