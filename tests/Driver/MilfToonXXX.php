<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MilfToonXXX extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://milftoon.xxx/comics/the-flintstones-porn/';
        $driver = new \Yamete\Driver\MilfToonXXX();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(32, count($driver->getDownloadables()));
    }
}
