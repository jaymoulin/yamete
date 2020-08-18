<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangatoonMobi extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangatoon.mobi/en/detail/2297';
        $driver = new \Yamete\Driver\MangatoonMobi();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(32, count($driver->getDownloadables()));
    }
}
