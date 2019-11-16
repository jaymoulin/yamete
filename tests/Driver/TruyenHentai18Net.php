<?php

namespace YameteTests\Driver;


class TruyenHentai18Net extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://truyenhentai18.net/pantsumon-2.html';
        $driver = new \Yamete\Driver\TruyenHentai18Net();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
