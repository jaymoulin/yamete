<?php

namespace YameteTests\Driver;


class SexyFuckGamersCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://sexyfuckgamers.com/e5aa613e7de1edc10e1e7da103cfb48b-honey-deep';
        $driver = new \Yamete\Driver\SexyFuckGamersCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
