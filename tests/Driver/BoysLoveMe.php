<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class BoysLoveMe extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://boyslove.me/boyslove/tattoo-violence/';
        $driver = new \Yamete\Driver\BoysLoveMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(45, count($driver->getDownloadables()));
    }
}
