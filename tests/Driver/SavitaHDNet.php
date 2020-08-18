<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class SavitaHDNet extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://savitahd.net/velamma-episode-95/';
        $driver = new \Yamete\Driver\SavitaHDNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(21, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadExt()
    {
        $url = 'https://savitahd.net/savita-18-episode-5-m/';
        $driver = new \Yamete\Driver\SavitaHDNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(31, count($driver->getDownloadables()));
    }
}
