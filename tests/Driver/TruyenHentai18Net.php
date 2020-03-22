<?php

namespace YameteTests\Driver;


class TruyenHentai18Net extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://truyenhentai18.net/love-strategy.html';
        $driver = new \Yamete\Driver\TruyenHentai18Net();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(19, count($driver->getDownloadables()));
    }
}
