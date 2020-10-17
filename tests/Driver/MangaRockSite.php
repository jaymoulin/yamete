<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaRockSite extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://mangarock.site/manga/free-dj-osananajimi-to-shichakushitsu-ni-iru-to';
        $driver = new \Yamete\Driver\MangaRockSite();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(56, count($driver->getDownloadables()));
    }
}
