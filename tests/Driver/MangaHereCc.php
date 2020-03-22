<?php

namespace YameteTests\Driver;


class MangaHereCc extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangahere.cc/manga/to_love_ru_kentaro_yabuki_20th_anniversary/';
        $driver = new \Yamete\Driver\MangaHereCc();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(33, count($driver->getDownloadables()));
    }
}
