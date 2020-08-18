<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ToonPornMe extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://toonporn.me/content/riviera-moon-goddess/index.html';
        $driver = new \Yamete\Driver\ToonPornMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(49, count($driver->getDownloadables()));
    }
}
