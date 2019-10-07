<?php

namespace YameteTests\Driver;


class ManyToonCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manytoon.com/comic/the-secret-club/';
        $driver = new \Yamete\Driver\ManyToonCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(23, count($driver->getDownloadables()));
    }
}
