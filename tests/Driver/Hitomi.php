<?php

namespace YameteTests\Driver;


class Hitomi extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hitomi.la/galleries/1084281.html';
        $driver = new \Yamete\Driver\Hitomi();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadThird()
    {
        $url = 'https://hitomi.la/galleries/1495922.html';
        $driver = new \Yamete\Driver\Hitomi();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
