<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ManhwaHentaiMe extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manhwahentai.me/webtoon/tell-me-you-love-me-webtoon-manhwa-hentai/';
        $driver = new \Yamete\Driver\ManhwaHentaiMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1951, count($driver->getDownloadables()));
    }
}
