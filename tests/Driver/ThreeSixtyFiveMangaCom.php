<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ThreeSixtyFiveMangaCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://365manga.com/manga/i-was-born-as-the-demon-lords-daughter/';
        $driver = new \Yamete\Driver\ThreeSixtyFiveMangaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(840, count($driver->getDownloadables()));
    }
}
