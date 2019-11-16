<?php

namespace YameteTests\Driver;


class HComicIn extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hcomic.in/ja/s/92648/';
        $driver = new \Yamete\Driver\HComicIn();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
