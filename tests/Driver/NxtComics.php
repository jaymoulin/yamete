<?php

namespace YameteTests\Driver;


class NxtComics extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://nxt-comics.com/seiren-adventures-lia-7-part-1/';
        $driver = new \Yamete\Driver\NxtComics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
