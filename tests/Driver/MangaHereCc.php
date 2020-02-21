<?php

namespace YameteTests\Driver;


class MangaHereCc extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangahere.cc/manga/futari_ecchi/v18/c205/1.html';
        $driver = new \Yamete\Driver\MangaHereCc();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(5061, count($driver->getDownloadables()));
    }
}
