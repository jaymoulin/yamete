<?php

namespace YameteTests\Driver;


class ThreeSixtyFiveMangaCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://365manga.com/manga/i-was-born-as-the-demon-lords-daughter/';
        $driver = new \Yamete\Driver\ThreeSixtyFiveMangaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(430, count($driver->getDownloadables()));
    }
}
