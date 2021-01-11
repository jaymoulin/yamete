<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaKakalotsCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://ww2.mangakakalots.com/chapter/vv921198/chapter_1';
        $driver = new \Yamete\Driver\MangaKakalotsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(42, count($driver->getDownloadables()));
    }
}
