<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class PornComixInfoNet extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://porncomixinfo.net/chapter/scooby-toon-1-4-english/';
        $driver = new \Yamete\Driver\PornComixInfoNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(126, count($driver->getDownloadables()));
    }
}
