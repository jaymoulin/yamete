<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiIdTv extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownloadRead()
    {
        $url = 'http://www.hentai-id.tv/manga.php?id=40017';
        $driver = new \Yamete\Driver\HentaiIdTv();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hentai-id.tv/tsuyu-chan-to-boku-no-hero-academia-esp/';
        $driver = new \Yamete\Driver\HentaiIdTv();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(25, count($driver->getDownloadables()));
    }
}
