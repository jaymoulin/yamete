<?php

namespace YameteTests\Driver;


class HentaiZoneMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaizone.me/mind-control-ch-4/';
        $driver = new \Yamete\Driver\HentaiZoneMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(16, count($driver->getDownloadables()));
    }
}
