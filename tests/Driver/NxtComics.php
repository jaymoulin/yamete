<?php

namespace YameteTests\Driver;


class NxtComics extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://nxt-comics.net/porncomix/glassfish-engagement-the-legend-of-zelda/';
        $driver = new \Yamete\Driver\NxtComics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(17, count($driver->getDownloadables()));
    }
}
