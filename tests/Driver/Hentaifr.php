<?php

namespace YameteTests\Driver;


class Hentaifr extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaifr.net/forbidden-frontiers-vol-5-par-pokkuti-lecture-en-ligne/';
        $driver = new \Yamete\Driver\Hentaifr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(18, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadToBeSure()
    {
        $url = 'https://hentaifr.net/cait-x-vi-x-jinx-badcompzero-lecture-en-ligne/';
        $driver = new \Yamete\Driver\Hentaifr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
