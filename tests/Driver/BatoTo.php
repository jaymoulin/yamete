<?php

namespace YameteTests\Driver;


class BatoTo extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://bato.to/series/77210';
        $driver = new \Yamete\Driver\BatoTo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(256, count($driver->getDownloadables()));
    }
}
