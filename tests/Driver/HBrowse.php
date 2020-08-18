<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HBrowse extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hbrowse.com/13537/c00001';
        $driver = new \Yamete\Driver\HBrowse();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(18, count($driver->getDownloadables()));
    }
}
