<?php

namespace YameteTests\Driver;


class SimplyHentai extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadOriginal()
    {
        $url = 'http://original-work.simply-hentai.com/mushikago-infu-hen-ichi-ni';
        $driver = new \Yamete\Driver\SimplyHentai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(51, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadSeries()
    {
        $url = 'https://www.simply-hentai.com/wreck-it-ralph/%E3%82%B7%E3%83%A5%E3%82%AC%E3%83%BC%E3%83%BB%E3%83%A9%E3%83%83%E3%82%B7%E3%83%A5/';
        $driver = new \Yamete\Driver\SimplyHentai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
