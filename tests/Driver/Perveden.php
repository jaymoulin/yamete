<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class Perveden extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.perveden.com/en/en-manga/c91-candy-paddle-nemunemu-idol-prelude-the-idolmster-sidem-otokonoko-matome-hon-2013-2015-english-sw/1/1/';
        $driver = new \Yamete\Driver\Perveden();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
