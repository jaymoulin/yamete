<?php

namespace YameteTests\Driver;


class MangaHereOnline extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://mangahere.online/manga/futari-ecchi';
        $driver = new \Yamete\Driver\MangaHereOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(4925, count($driver->getDownloadables()));
    }
}
