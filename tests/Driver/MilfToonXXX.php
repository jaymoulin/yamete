<?php

namespace YameteTests\Driver;


class MilfToonXXX extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
