<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiHere extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaihere.com/m/S12135/';
        $driver = new \Yamete\Driver\HentaiHere();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(92, count($driver->getDownloadables()));
    }
}
