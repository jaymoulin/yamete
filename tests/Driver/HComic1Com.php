<?php

namespace YameteTests\Driver;


class HComic1Com extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hcomic1.com/ja/s/92648/';
        $driver = new \Yamete\Driver\HComic1Com();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
