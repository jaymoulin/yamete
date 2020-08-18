<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class Palcomix extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://palcomix.com/princessmarco/index.html';
        $driver = new \Yamete\Driver\Palcomix();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(45, count($driver->getDownloadables()));
    }
}
