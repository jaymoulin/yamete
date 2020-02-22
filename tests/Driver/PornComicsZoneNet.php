<?php

namespace YameteTests\Driver;


class PornComicsZoneNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://porncomicszone.net/22/glamourous-notzack-forwork/549501/';
        $driver = new \Yamete\Driver\PornComicsZoneNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
