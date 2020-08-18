<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ToonSexPics extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownloadSerie()
    {
        $url = 'http://www.toonsex.pics/galleries/cumming-inside-mommy-s-hole-vol-2-hentai-part-2';
        $driver = new \Yamete\Driver\ToonSexPics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(129, count($driver->getDownloadables()));
    }
}
