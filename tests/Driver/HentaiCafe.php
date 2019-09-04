<?php

namespace YameteTests\Driver;


class HentaiCafe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadAlternateFromUrl()
    {
        $url = 'https://hentai.cafe/shindou-ill-watch-the-house-until-youre-bigger/';
        $driver = new \Yamete\Driver\HentaiCafe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }
}
