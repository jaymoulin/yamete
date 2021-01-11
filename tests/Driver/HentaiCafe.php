<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiCafe extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentai.cafe/kohsaka-donten-once-bitten-twice-shy/';
        $driver = new \Yamete\Driver\HentaiCafe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadAlternateFromUrl()
    {
        $url = 'https://hentai.cafe/shindou-ill-watch-the-house-until-youre-bigger/';
        $driver = new \Yamete\Driver\HentaiCafe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadNewUrlFormat()
    {
        $url = 'https://hentai.cafe/hc.fyi/3369';
        $driver = new \Yamete\Driver\HentaiCafe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
