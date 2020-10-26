<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class Manga3sCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manga3s.com/manga/chief-seduction-officer/';
        $driver = new \Yamete\Driver\Manga3sCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1705, count($driver->getDownloadables()));
    }
}
