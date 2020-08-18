<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class Erofus extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.erofus.com/comics/zzz-comics/agw-house-party';
        $driver = new \Yamete\Driver\Erofus();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(40, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadChapter()
    {
        $url = 'https://www.erofus.com/comics/zzz-comics/agw-house-party/issue-1';
        $driver = new \Yamete\Driver\Erofus();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
