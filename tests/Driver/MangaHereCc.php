<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaHereCc extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangahere.cc/manga/to_love_ru_kentaro_yabuki_20th_anniversary/';
        $driver = new \Yamete\Driver\MangaHereCc();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(35, count($driver->getDownloadables()));
    }
}
