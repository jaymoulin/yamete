<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class Taadd extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.taadd.com/book/Ookii+Onnanoko+wa+Daisuki+Desu+ka%3F.html';
        $driver = new \Yamete\Driver\Taadd();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1698, count($driver->getDownloadables()));
    }
}
