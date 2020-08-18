<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaHereClub extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangahere.club/manga/nyotaika-yankee-gakuen-ore-no-hajimete-nerawaretemasu';
        $driver = new \Yamete\Driver\MangaHereClub();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(269, count($driver->getDownloadables()));
    }
}
