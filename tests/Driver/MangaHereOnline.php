<?php

namespace YameteTests\Driver;


class MangaHereOnline extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://mangahere.online/manga/touhou-project-dj-ecchi-na-nekomimi';
        $driver = new \Yamete\Driver\MangaHereOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }
}
