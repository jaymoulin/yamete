<?php

namespace YameteTests\Driver;


class ToonSexPics extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.toonsex.pics/galleries/cumming-inside-mommy-s-hole-vol-2-hentai';
        $driver = new \Yamete\Driver\ToonSexPics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
