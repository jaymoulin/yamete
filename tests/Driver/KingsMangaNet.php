<?php

namespace YameteTests\Driver;


class KingsMangaNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.kingsmanga.net/manga/chronicles-of-heavenly-demon/';
        $driver = new \Yamete\Driver\KingsMangaNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(755, count($driver->getDownloadables()));
    }
}
