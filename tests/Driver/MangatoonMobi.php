<?php

namespace YameteTests\Driver;


class MangatoonMobi extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangatoon.mobi/en/detail/2297';
        $driver = new \Yamete\Driver\MangatoonMobi();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(32, count($driver->getDownloadables()));
    }
}
