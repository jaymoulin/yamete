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
        $url = 'https://manhwahentai.me/webtoon/new-teacher-in-town-webtoon-manhwa-hentai/';
        $driver = new \Yamete\Driver\ManhwaHentaiMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1487, count($driver->getDownloadables()));
    }
}
