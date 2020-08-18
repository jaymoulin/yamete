<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class RawdevartCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://rawdevart.com/comic/shokushu-majutsu-shi-no-nariagari/';
        $driver = new \Yamete\Driver\RawdevartCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(194, count($driver->getDownloadables()));
    }
}
